#!/usr/bin/perl

# Copyright (c) 2005-2006, Gea-Suan Lin.
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#    notice, this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
#    notice, this list of conditions and the following disclaimer in the
#    documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.

require('/news/lib/innshellvars.pl');

use Carp;
use DBI;
use Net::NNTP;
use strict;

my ($dbhost, $dbuser, $dbpass, $dbname);

&load_dbdata();
&main();

sub addmid($$)
{
    my $mid = shift;
    my $n = shift;

    $mid =~ /^<(.*)>$/;
    return sprintf("<%s.grouppost.%s>", $1, $n);
}

sub getart($)
{
    my $token = shift();

    my $sm = $inn::pathbin . "/sm";

    open(SM, sprintf('%s %s |', $sm, quotemeta($token))) or croak('Unable to open SM');

    my (@head, @body);

    while (my $str = <SM>) {
	push(@head, $str);
	chomp($str);
	if ($str =~ /^$/) {
	    pop(@head);
	    last;
	}
    }

    @body = <SM>;

    close(SM);

    return (\@head, \@body);
}

sub load_dbdata()
{
    chdir($ENV{'HOME'}) if (defined($ENV{'HOME'}));

    open(F, 'etc/dbdata.conf');

    while (<F>) {
	chomp();
	next if (/^\s*$/ || /^#/);

	if (/^(.+?)=(.*)$/) {
	    $dbhost = $2 if ($1 eq 'dbhost');
	    $dbuser = $2 if ($1 eq 'dbuser');
	    $dbpass = $2 if ($1 eq 'dbpass');
	    $dbname = $2 if ($1 eq 'dbname');
	}
    }

    close(F);
}

sub main()
{
    while (my $token = <STDIN>) {
	chomp($token);

	my ($cancel, @head2, @headlist, $mid, @ngs);

	my ($head, $body) = getart($token);

	foreach my $l (@$head) {
	    next unless ($l =~ /^([^:]+):\s+(.*)\r?\n?$/);

	    if ($1 eq 'Control' && $2 =~ /^cancel\s+(.*)$/) {
		$cancel = $1;
		next;
	    } elsif ($1 eq 'Message-ID') {
		$mid = $2;
		next;
	    } elsif ($1 eq 'Newsgroups') {
		# Group post.
		foreach my $ng (split(/,+/, $2)) {
		    next unless ($ng =~ /^group\.([^.]+)\.([^.]+)$/);

		    my $host = $1;
		    my $groupname = $2;

		    my $dbi = sprintf('dbi:mysql:database=%s;host=%s', $dbname, $dbhost);
		    my $dbh = DBI->connect($dbi, $dbuser, $dbpass) or croak(DBI::errstr());
		    my $qstr = sprintf('SELECT host FROM bbs WHERE groupname=%s;', $dbh->quote($groupname));

		    foreach my $h (@{$dbh->selectcol_arrayref($qstr)}) {
			next if ($host eq $h);
			push(@ngs, sprintf('group.%s.%s', $h, $groupname));
		    }

		    $dbh->disconnect();
		}

		next;
	    } elsif ($1 eq 'Path') {
		$l = "Path: grouppost!$2\r\n";
	    } elsif ($1 eq 'Subject') {
		next if ($2 =~ /^cmsg\s+cancel\s+$/);
	    }

	    push(@head2, $l);
	}

	my $nntp = Net::NNTP->new('group.nctu.edu.tw', Port => 433) or croak("$!");

	foreach my $n (@ngs) {
	    my $newmid = &transmid($mid, $n);

#	    print($newmid, "\r\n");

	    if ($cancel) {
		$nntp->ihave($newmid, @head2, sprintf("Newsgroups: %s\r\n", $n), sprintf("Message-ID: %s\r\n", $newmid),
		sprintf("Control: cancel %s\r\n", $newmid), sprintf("Subject: cmsg cancel %s\r\n", $newmid), "\r\n", @$body);
	    } else {
		$nntp->ihave($newmid, @head2, sprintf("Newsgroups: %s\r\n", $n), sprintf("Message-ID: %s\r\n", $newmid), "\r\n", @$body);
	    }
	}

	$nntp->quit();
    }
}

sub transmid($$)
{
    my $mid = shift;
    my $n = shift;

    if ($mid =~ /^<(.+)\.grouppost\.(.+)>$/) {
	return sprintf('<%s.grouppost.%s>', $1, $n);
    } else {
	return &addmid($mid, $n);
    }
}


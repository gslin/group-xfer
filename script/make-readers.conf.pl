#!/usr/bin/perl

# Copyright (c) 2005-2009, Gea-Suan Lin.
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

use Carp;
use Digest::MD5 qw/md5_hex/;
use DBI;
use IO::Select;
use Net::DNS;
use strict;

my ($dbhost, $dbuser, $dbpass, $dbname);

my $VERSION = '20090603';

my %host;
my $sel = undef;

&load_dbdata();
&main();

sub do_check($)
{
    my $timeout = shift;

    my $res = Net::DNS::Resolver->new();

    # check
    my @ready = $sel->can_read($timeout);
    foreach my $sock (@ready) {
	my $packet = $res->bgread($sock);

	do_entry($packet);

	$sel->remove($sock);
    }
}

sub do_entry($)
{
    my $packet = shift;

    croak('No packet.') unless (defined($packet));

    my @q = $packet->question();
    return unless (@q);

    $q[0]->qname() =~ /^([^.]+)\./i;
    my $h = $1;

    my @r = $packet->answer();
    foreach (@r) {
	next unless ($_->type() eq 'A');

	my $ip = $_->rdatastr();

	if (defined($host{$ip})) {
	    $host{$ip} .= '_' . $h;
	} else {
	    $host{$ip} = $h;
	}
    }
}

sub do_query($)
{
    my $host = shift;

    if ($host eq '') {
	carp('No hostname.');
	return;
    }

    my $res = Net::DNS::Resolver->new();
    my $sock = $res->bgsend($host);

    # add to it.
    if (defined($sel)) {
	$sel->add($sock);
    } else {
	$sel = IO::Select->new($sock);
    }

    # check
    do_check(0.1);
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
    my $dbi = sprintf('dbi:mysql:database=%s;host=%s', $dbname, $dbhost);
    my $dbh = DBI->connect($dbi, $dbuser, $dbpass) or croak(DBI::errstr());

    printf("#\n\n");

    # get all host ip
    foreach my $h (@{$dbh->selectcol_arrayref('SELECT DISTINCT host FROM bbs')}) {
	do_query($h . '.twbbs.org.');
    }

    my $count = 30;
    while ($count-- > 0 && $sel->count() > 0) {
	do_check(1);
    }

    # auth
    foreach my $h (keys(%host)) {
	printf("auth \"news_%s\"\n{\n", md5_hex($host{$h}));
	printf("\thosts:\t\t\"%s\"\n", $h);
	printf("\tdefault:\t\"%s\"\n", md5_hex($host{$h}));

	print(<<EOF
	default-domain:	"BBS"

}
EOF
);
    }

    print(<<EOF
auth "CC"
{
	hosts:		"ccreader.nctu.edu.tw, 140.113.54.119"
	default:	"netnews"
	default-domain:	"cc.nctu.edu.tw"
}

auth "LOCAL"
{
	hosts:		"group.nctu.edu.tw, 140.113.54.117, localhost, 127.0.0.1"
	default:	"LOCAL"
	default-domain:	"LOCAL"
}

##############################################################################

EOF
);

    # access
    foreach my $h (keys(%host)) {
	printf("access \"news_%s\"\n{\n", md5_hex($host{$h}));
	printf("\tusers:\t\t\"%s\@BBS\"\n", md5_hex($host{$h}));
	printf("\tnewsgroups:\t\"%s\"\n", join(',', map(sprintf('group.%s.*', $_), split(/_/, $host{$h}), 'public'), 'control.cancel'));

	print(<<EOF
	access:		"R P"
}

EOF
);
    }

    print(<<EOF
access "CC"
{
	users:		"*\@cc.nctu.edu.tw"
	newsgroups:	"*"
	access:		"R P\"
}

access "LOCAL"
{
	users:		"*\@LOCAL"
	newsgroups:	"*"
	access:		"R P"
}

EOF
);
}

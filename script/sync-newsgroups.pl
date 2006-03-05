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
use strict;

require('db.pl');

my ($dbhost, $dbuser, $dbpass, $dbname);
my %ngs;
my (@ngs_add, @ngs_del);

&load_dbdata();
&main();

sub check_ngs()
{
    my $dbi = sprintf('dbi:mysql:database=%s;host=%s', $dbname, $dbhost);
    my $dbh = DBI->connect($dbi, $dbuser, $dbpass) or croak();

    my $a1_ref = $dbh->selectall_arrayref('SELECT DISTINCT groupname, host FROM bbs;');
    foreach my $a2_ref (@{$a1_ref}) {
	my $groupname = $$a2_ref[0];
	my $host = $$a2_ref[1];

	my $ng = ngname($groupname, $host);

	# if existed, okay.
	if (defined($ngs{$ng})) {
	    delete($ngs{$ng});
	    next;
	}

	# if not existed, add into ngs_add
	push(@ngs_add, $ng);
    }

    # otherwise, it should be deleted.
    @ngs_del = keys(%ngs);
}

sub commit_changes()
{
    my $str;

    foreach (@ngs_add) {
	$str = sprintf("%s/ctlinnd newgroup %s y usenet\@group.nctu.edu.tw", $inn::newsbin, quotemeta($_));
	print($str, "\n");
	system($str);
    }

    foreach (@ngs_del) {
	$str = sprintf("%s/ctlinnd rmgroup %s", $inn::newsbin, quotemeta($_));
	print($str, "\n");
	system($str);
    }
}

sub load_activedb()
{
    open(F, $inn::active);

    while (<F>) {
	chomp;
	next if (/^\s*$/);

	# first column
	if (/^group\./ && /^(\S+)/) {
	    $ngs{$1} = 1;
	}
    }

    close(F);
}

sub main()
{
    &load_activedb();
    &check_ngs();
    &commit_changes();
}

sub ngname($$)
{
    my $groupname = shift();
    my $host = shift();

    return (sprintf("group.%s.%s", $host, $groupname));
}

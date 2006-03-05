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

use Carp;
use DBI;
use strict;

require('db.pl');

my ($dbhost, $dbuser, $dbpass, $dbname);

&load_dbdata();
&main();

sub main()
{
    my $dbi = sprintf('dbi:mysql:database=%s;host=%s', $dbname, $dbhost);
    my $dbh = DBI->connect($dbi, $dbuser, $dbpass) or croak(DBI::errstr());

    open(F, "> /news/etc/aliases-group");

    my $qstr = 'SELECT groupname FROM groups WHERE mailpost="Y"';
    foreach my $grpname (@{$dbh->selectcol_arrayref($qstr)})
    {
	my $qstr = sprintf('SELECT host FROM bbs WHERE groupname=%s LIMIT 1', $dbh->quote($grpname));
	my $host = ${$dbh->selectcol_arrayref($qstr)}[0];

	printf(F "group.%s:\t\"| /news/bin/mailpost group.%s.%s\"\n", $grpname, $host, $grpname);
    }

    close(F);

    system("/usr/local/sbin/postalias hash:/news/etc/aliases-group");
}

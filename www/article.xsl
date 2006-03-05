<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="/screen.css" type="text/css" title="basic" media="screen, projection" />
	<title><xsl:value-of select="article/title" disable-output-escaping="yes" /></title>
</head>

<body>

<div class="header">
	<h1 class="author"><span class="subject">作者</span><span class="co"><xsl:value-of select="article/author" disable-output-escaping="yes" /></span></h1>
	<h1 class="title"><span class="subject">標題</span><span class="co"><xsl:value-of select="article/title" disable-output-escaping="yes" /></span></h1>
</div>

<div class="content">
	<pre><xsl:value-of select="article/description" disable-output-escaping="yes" /></pre>
</div>

</body>

</html>

</xsl:template>
</xsl:stylesheet>

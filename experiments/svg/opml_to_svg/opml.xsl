<?xml version="1.0"?> 
<xsl:stylesheet 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:p7="http://www.protocol7.com/ns" 
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns="http://www.w3.org/2000/svg"
	version="1.0">
	<xsl:output method="html" indent="no" omit-xml-declaration="no"/>

<!-- 
Niklas Gustavsson
niklas@protocol7.com
License at http://www.protocol7.com/default.asp?x=license
 -->
	
	<xsl:template match="/">
		<svg xmlns:p7="http://www.protocol7.com/ns"  xmlns:a3="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" a3:scriptImplementation="Adobe" onload="_onload()">
			<title>OPML to SVG</title>
			<style type="text/css">
				text{pointer-events:none}
			</style>
			<script xlink:href="opml.js" />
			<text x="25" y="25" fill="black" font-size="16px">click the squares to explore</text>
				<svg id="kalle" viewBox="0 0 162 162" x="25" y="30" width="400" height="400" onclick="_onclick(evt)">
					
					<xsl:apply-templates />
				</svg>
		    <text id="hist" x="25" y="450" fill="#CCC" font-size="18px">as</text>
			
		</svg>
	</xsl:template>		

	<xsl:template match="head/title">
		<script>
			title = "<xsl:value-of select="text()" />"
		</script>
	</xsl:template>

	<xsl:template match="body">
		<xsl:apply-templates />
	</xsl:template>

	<xsl:template match="outline/outline">
	    <xsl:variable name="outlinePos" select="count(following-sibling::outline) + 1"></xsl:variable>
		<g >
			<xsl:attribute name="transform">translate(<xsl:choose>
			    <xsl:when test="$outlinePos = 1">54,54</xsl:when>
			    <xsl:when test="$outlinePos = 2">27,54</xsl:when>
			    <xsl:when test="$outlinePos = 3">54,27</xsl:when>
			    <xsl:when test="$outlinePos = 4">27,27</xsl:when>
			    <xsl:when test="$outlinePos = 5">0,54</xsl:when>
			    <xsl:when test="$outlinePos = 6">54,0</xsl:when>
			    <xsl:when test="$outlinePos = 7">0,27</xsl:when>
			    <xsl:when test="$outlinePos = 8">27,0</xsl:when>
			    <xsl:when test="$outlinePos = 9">0,0</xsl:when>
				</xsl:choose>) scale(0.33333)</xsl:attribute>
			<xsl:if test="@url">
				<xsl:attribute name="p7:url"><xsl:value-of select="@url" /></xsl:attribute>
			</xsl:if>
			<rect x="0" y="0" width="81" height="81" fill="black">
				<xsl:attribute name="fill-opacity"><xsl:choose>
				    <xsl:when test="$outlinePos = 1">0.7</xsl:when>
				    <xsl:when test="$outlinePos = 2">0.5</xsl:when>
				    <xsl:when test="$outlinePos = 3">0.5</xsl:when>
				    <xsl:when test="$outlinePos = 4">0.3</xsl:when>
				    <xsl:when test="$outlinePos = 5">0.3</xsl:when>
				    <xsl:when test="$outlinePos = 6">0.3</xsl:when>
				    <xsl:when test="$outlinePos = 7">0.2</xsl:when>
				    <xsl:when test="$outlinePos = 8">0.2</xsl:when>
				    <xsl:when test="$outlinePos = 9">0.1</xsl:when>
					</xsl:choose></xsl:attribute>
			</rect>
			<text x="5" y="15" fill="white"><xsl:value-of select="@text" /></text>

			<xsl:apply-templates />
		</g>
	</xsl:template>
	
	
	<xsl:template match="outline">
	    <xsl:variable name="outlinePos" select="count(following-sibling::outline) + 1"></xsl:variable>
		<g>
		   
			<xsl:attribute name="transform"><xsl:choose>
			    
			    <xsl:when test="$outlinePos = 1"></xsl:when>
			    <xsl:when test="$outlinePos = 2">translate(0,81)</xsl:when>
			    <xsl:when test="$outlinePos = 3">translate(81,0)</xsl:when>
			    <xsl:when test="$outlinePos = 4">translate(81,81)</xsl:when>
				</xsl:choose></xsl:attribute>
			<xsl:if test="@url">
				<xsl:attribute name="p7:url"><xsl:value-of select="@url" /></xsl:attribute>
			</xsl:if>

			<rect x="0" y="0" width="81" height="81">
				<xsl:attribute name="fill"><xsl:choose>
				    <xsl:when test="$outlinePos = 1">red</xsl:when>
				    <xsl:when test="$outlinePos = 2">blue</xsl:when>
				    <xsl:when test="$outlinePos = 3">yellow</xsl:when>
				    <xsl:when test="$outlinePos= 4">green</xsl:when>
					</xsl:choose></xsl:attribute>
			</rect>
			<text x="5" y="15" fill="white"><xsl:value-of select="@text" /></text>

			<xsl:apply-templates />
		</g>
	</xsl:template>
    
                <xsl:template match="text()" />
                    
                    

	
</xsl:stylesheet>

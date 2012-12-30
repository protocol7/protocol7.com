<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg"  xmlns:v="urn:schemas-microsoft-com:vml">
	<xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes"/>

			<xsl:attribute-set name="font">
				<xsl:attribute name="fname">Arial</xsl:attribute>
				<xsl:attribute name="size">14px</xsl:attribute>
				<xsl:attribute name="color">red</xsl:attribute>
			</xsl:attribute-set>

	<xsl:template match="/">
		<xsl:apply-templates />
	</xsl:template>
	
	<xsl:template match="xhtml:* | @* | text()">
		<xsl:copy>
			<xsl:apply-templates />
		</xsl:copy>
	</xsl:template>

	<xsl:template match="xhtml:head">
		<xsl:copy>
			<style>
				v\:* { behavior: url(#default#VML); }
			</style>
			<xsl:apply-templates />
		</xsl:copy>
	</xsl:template>
	
	<xsl:template match="svg:rect">
		<v:rect>
			<xsl:attribute name="style"><xsl:apply-templates select="@*" mode="style"/></xsl:attribute>
			<xsl:apply-templates select="@*" mode="nonstyle"/>
			<xsl:apply-templates select="*"/>
		</v:rect>
	</xsl:template>
	
	<xsl:template match="@fill" mode="nonstyle">
		<xsl:attribute name="fillcolor"><xsl:value-of select="." /></xsl:attribute>
	</xsl:template>

	<xsl:template match="@width|@height" mode="style"><xsl:value-of select="local-name()" />:<xsl:value-of select="." />;</xsl:template>

	<xsl:template match="@x|@y" mode="style">position:relative;<xsl:value-of select="local-name()" />:<xsl:value-of select="." />;</xsl:template>

	<xsl:template match="@*" mode="nonstyle"></xsl:template>
	<xsl:template match="@*" mode="style"></xsl:template>


</xsl:stylesheet>

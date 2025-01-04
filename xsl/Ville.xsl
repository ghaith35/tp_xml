<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title><xsl:value-of select="ville/@nom"/></title>
            </head>
            <body>
                <h1><xsl:value-of select="ville/@nom"/> - Détails</h1>
                <p><xsl:value-of select="ville/descriptif"/></p>
                <h2>Sites</h2>
                <ul>
                    <xsl:for-each select="ville/sites/site">
                        <li><xsl:value-of select="nom"/> - <img src="{photo}" alt="Site image"/></li>
                    </xsl:for-each>
                </ul>
                <h2>Hôtels</h2>
                <ul>
                    <xsl:for-each select="ville/hotels/hotel">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Restaurants</h2>
                <ul>
                    <xsl:for-each select="ville/restaurants/restaurant">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Gares</h2>
                <ul>
                    <xsl:for-each select="ville/gares/gare">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Aéroports</h2>
                <ul>
                    <xsl:for-each select="ville/aeroports/aeroport">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>

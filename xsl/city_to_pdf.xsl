<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>City Report</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1, h2, h3 { color: #333; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>City Report: <xsl:value-of select="/ville/@nom"/></h1>
                <p><xsl:value-of select="/ville/descriptif"/></p>
                <h2>Sites</h2>
                <table>
                    <tr>
                        <th>Site Name</th>
                        <th>Photo</th>
                    </tr>
                    <xsl:for-each select="/ville/sites/site">
                        <tr>
                            <td><xsl:value-of select="@nom"/></td>
                            <td><xsl:value-of select="photo"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
                <h2>Hotels</h2>
                <ul>
                    <xsl:for-each select="/ville/hotels/hotel">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Restaurants</h2>
                <ul>
                    <xsl:for-each select="/ville/restaurants/restaurant">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Gares</h2>
                <ul>
                    <xsl:for-each select="/ville/gares/gare">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
                <h2>Aeroports</h2>
                <ul>
                    <xsl:for-each select="/ville/aeroports/aeroport">
                        <li><xsl:value-of select="."/></li>
                    </xsl:for-each>
                </ul>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
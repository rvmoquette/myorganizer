<?php declare(strict_types=1);

/**
 * Convert SimpleXMLElement to array
 *
 * $xml the SimpleXMLElement
 *
 * $listSimpleNodes is need to detect if a node have a single childe in order to have always the same structue of the result.
 *
 */
class XmlToArray
{
    private $xml;
    private $listSimpleNodes;

    public function __construct() {
        $this->xml = null;
        $this->listSimpleNodes = [];
    }

    public function setXml(SimpleXMLElement $xml): void
    {
        $this->xml = $xml;
    }

    /**
     * Dans le cas ou il n'y a qu'un seul élément, alors groupeBy permet de conserver une structure homogène.
     *
     * @param array $listSimpleNodes
     */
    public function groupeBy(array $listSimpleNodes): void
    {
        $this->listSimpleNodes = $listSimpleNodes;
    }

    public function convertToArray(): array
    {
        return $this->xmlToArray($this->xml);
    }

    private function xmlToArray(SimpleXMLElement $xml): array
    {
        $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
            $nodes = $xml->children();
            $attributes = $xml->attributes();

            if (0 !== count($attributes)) {
                foreach ($attributes as $attrName => $attrValue) {
                    $collection['@'][$attrName] = strval($attrValue);
                }
            }

            if (0 === $nodes->count()) {
                $collection['%'] = strval($xml);
                return $collection;
            }

            foreach ($nodes as $nodeName => $nodeValue) {
                if (count($nodeValue->xpath('../' . $nodeName)) < 2 && ! in_array($nodeName, $this->listSimpleNodes)) {
                    $collection[$nodeName] = $parser($nodeValue);
                } else {
                    $collection[][$nodeName] = $parser($nodeValue);
                }
            }

            return $collection;
        };

        return [
            $xml->getName() => $parser($xml)
        ];
    }

    public function xmlMap(): string
    {
        return $this->analyseXml($this->convertToArray());
    }

    private function analyseXml(array $xml, array $niv = [], int $i = 0): string
    {
        $xmlMap = '';
        foreach ($xml as $key => $val) {
            $fin = false;
            if (is_string($key)) {
                $niv[$i] = "'$key'";
            } else {
                $niv[$i] = '...';
                if ($key>0) {
                    $fin = true;
                }
            }
            if (! $fin) {
                if (is_array($val)) {
                    $xmlMap .= $this->analyseXml($val, $niv, $i+1);
                } else {
                    $xmlMap_t = "<div style='padding: 10px;'>";
                    foreach ($niv as $k) {
                        if ($k == "...") {
                            $xmlMap_t .= "<b>[$k]</b>";
                        } else {
                            $xmlMap_t .= "[$k]";
                        }
                    }
                    $xmlMap_t .= " <i style='color: red;'>(exemple : " . htmlspecialchars($val) . ")</i>";
                    $xmlMap_t .= '</div>';
                    $xmlMap .= $xmlMap_t;
                }
            }
        }
        return $xmlMap;
    }
}

<?php
namespace DXFWriter;

class Shape
{
    public $fillColor;
    public $fillType;
    public $origin;
    public $lineColor;
    public $lineType;
    public $lineWeight;

    public $xPosition;
    public $yPosition;

    public $type;

    public function setOptions($optionsGiven = null)
    {
        $optionsGiven = is_null($optionsGiven) ? [] : $optionsGiven;
        $options = array_replace($this->getDefaults(), $optionsGiven);

        $this->fillColor = $this->setColor($options["fillColor"]);
        $this->fillType = $options["fillType"];
        $this->origin = $options["origin"];
        $this->lineColor = $this->setColor($options["lineColor"]);
        $this->lineWeight = $this->setLineWeight($options["lineWeight"]);
        $this->lineType = $options["lineType"];
    }

    public function setColor($color)
    {
        // Note that AutoCAD 'white' prints black if on a white background
        $colors = ["black", "red", "yellow", "green", "cyan", "blue", "violet", "white"];

        $index = array_search(strtolower($color), $colors);
        if ($index !== false){
            return $index;
        }

        if (is_int($color) && $color >= 0 && $color < count($colors)){
            return $color;
        }

        return 0;
    }

    public function setLineWeight($givenWeight)
    {
        $lineWeights = [0, 0.05, 0.09, 0.13, 0.15, 0.18, 0.2, 0.25, 0.3, 0.35, 0.4, 0.5,
                0.53, 0.6, 0.7, 0.8, 0.9, 1.0, 1.06, 1.2, 1.4, 1.58, 2.0, 2.11,
        ];

        $previousWeight = 0;
        foreach($lineWeights as $index => $weight){
            if ($index > 0){
                if($weight > $givenWeight){
                    if ($weight == 0.05 && $givenWeight != 0.0){
                        return $weight;
                    }
                    return ($weight - $givenWeight) < ($givenWeight - $previousWeight) ? $weight : $previousWeight;
                }
            }

            $previousWeight = $weight;
        }

        return 2.11;
    }

    public function getDefaults()
    {
        return array(
                "fillColor" => "green",
                "fillType" => "solid",
                "origin" => "bottom-left",
                "lineColor" => "0",
                "lineWeight" => 0.13,
                "lineType" => "solid",
        );
    }

}
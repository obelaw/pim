<?php

namespace Obelaw\Pim\Services;

use Obelaw\Pim\Models\UnitOfMeasure;
use Obelaw\Pim\Models\UomConversion;
use Exception;

class UomConverter
{
    /**
     * Convert a quantity from one UOM to another.
     *
     * @param float $quantity The quantity to convert.
     * @param UnitOfMeasure|int $fromUom The source UOM instance or ID.
     * @param UnitOfMeasure|int $toUom The target UOM instance or ID.
     * @return float The converted quantity.
     * @throws Exception If conversion is not possible or not defined.
     */
    public function convert(float $quantity, UnitOfMeasure|int $fromUom, UnitOfMeasure|int $toUom): float
    {
        $fromId = $fromUom instanceof UnitOfMeasure ? $fromUom->id : $fromUom;
        $toId = $toUom instanceof UnitOfMeasure ? $toUom->id : $toUom;

        if ($fromId === $toId) {
            return $quantity;
        }

        // 1. Direct Conversion (From -> To)
        // e.g., Box -> Piece (Factor 12). 1 Box = 12 Pieces.
        $directConversion = UomConversion::where('from_uom_id', $fromId)
            ->where('to_uom_id', $toId)
            ->first();

        if ($directConversion) {
            return $quantity * $directConversion->conversion_factor;
        }

        // 2. Inverse Conversion (To -> From)
        // e.g., Piece -> Box. We only have Box -> Piece (12).
        // So 1 Piece = 1/12 Box.
        $inverseConversion = UomConversion::where('from_uom_id', $toId)
            ->where('to_uom_id', $fromId)
            ->first();

        if ($inverseConversion) {
            if ($inverseConversion->conversion_factor == 0) {
                throw new Exception("Conversion factor cannot be zero.");
            }
            return $quantity / $inverseConversion->conversion_factor;
        }

        // 3. Indirect Conversion (via base unit, if logic extended)
        // For now, if no direct link, we throw exception.
        // In full WMS systems, multiple hops might be calculated (Box -> Pack -> Piece),
        // but that requires recursive search or a designated 'System Base UOM'.
        
        throw new Exception("No conversion defined between the specified units of measure.");
    }
}

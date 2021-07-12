<?php

class Cube
{
    private $coordinate;
    private $side;

    /**
     * Cube constructor.
     * @param Coordinate $coordinate
     * @param int $side
     */
    public function __construct(Coordinate $coordinate, int $side)
    {
        $this->coordinate = $coordinate;
        $this->side = $side;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    /**
     * @return int
     */
    public function getSide(): int
    {
        return $this->side;
    }

    /**
     * @param Cube $cube
     * @return float
     */
    public function getIntersectionVolume(Cube $cube): float
    {
        $maxClosestCoordinate = $this->coordinate->getMaxCoordinate($cube->getCoordinate());
        $minFurthestCoordinate = $this->getHigherCoordinate()->getMinCoordinate($cube->getHigherCoordinate());

        $base = $minFurthestCoordinate->getX() - $maxClosestCoordinate->getX();
        $hight = $minFurthestCoordinate->getY() - $maxClosestCoordinate->getY();
        $thickness = $minFurthestCoordinate->getZ() - $maxClosestCoordinate->getZ();
        return $base * $hight * $thickness;
    }

    public function getHigherCoordinate(): Coordinate
    {
        return new Coordinate(
            $this->coordinate->getX() + $this->side,
            $this->coordinate->getY() + $this->side,
            $this->coordinate->getZ() + $this->side
        );
    }
}


class Coordinate
{
    private $x;
    private $y;
    private $z;

    /**
     * Coordinate constructor.
     * @param integer $x
     * @param integer $y
     * @param integer $z
     */
    public function __construct(int $x, int $y, int $z, int $side=0)
    {
        $this->x = $x-$side/2;
        $this->y = $y-$side/2;
        $this->z = $z-$side/2;
    }

    /**
     * @param string $coordinate
     * @return Coordinate
     */
    public static function fromString(string $coordinate, int $side): self
    {
        static::validateString($coordinate);

        $coordinateArray = explode(',', $coordinate);
        return new self(
            (int) $coordinateArray[0],
            (int) $coordinateArray[1],
            (int) $coordinateArray[2],
			(int) $side
        );
    }

    /**
     * @param string $coordinate
     * @throws ErrorException
     */
    private static function validateString(string $coordinate): void
    {
        $coordinateArray = explode(',', $coordinate);

        if (count($coordinateArray) < 3) {
            throw new ErrorException('Invalid format');
        }
		
		  if (!is_numeric($coordinateArray[0] + 0)) {
            throw new ErrorException('Invalid format');
        }
        
        if (!is_numeric($coordinateArray[1] + 0)) {
            throw new ErrorException('Invalid format');
        }

        if (!is_numeric($coordinateArray[2] + 0)) {
            throw new ErrorException('Invalid format');
        }
    }

    /**
     * @param Coordinate $coordinate
     * @return Coordinate
     */
    public function getMaxCoordinate(Coordinate $coordinate): Coordinate
    {
        $maxX = ($this->x > $coordinate->getX()) ? $this->x : $coordinate->getX();
        $maxY = ($this->y > $coordinate->getY()) ? $this->y : $coordinate->getY();
        $maxZ = ($this->z > $coordinate->getZ()) ? $this->z : $coordinate->getZ();
        return new Coordinate($maxX, $maxY, $maxZ);
    }

    /**
     * @param Coordinate $coordinate
     * @return Coordinate
     */
    public function getMinCoordinate(Coordinate $coordinate): Coordinate
    {
        $minX = ($this->x < $coordinate->getX()) ? $this->x : $coordinate->getX();
        $minY = ($this->y < $coordinate->getY()) ? $this->y : $coordinate->getY();
        $minZ = ($this->z < $coordinate->getZ()) ? $this->z : $coordinate->getZ();
        return new Coordinate($minX, $minY, $minZ);
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->z;
    }
}

class CubeService
{
    /**
     * @param string $coordinate1
     * @param integer $edge1
     * @param string $coordinate2
     * @param integer $edge2
     * @return float
    */
    public function calculateIntersectionVolume(
        string $coordinate1,
        int $side1,
        string $coordinate2,
        int $side2
    ): float
    {
        $coordinate1 = Coordinate::fromString($coordinate1, $side1);
        $coordinate2 = Coordinate::fromString($coordinate2, $side2);
        $cube1 = new Cube($coordinate1, $side1);
        $cube2 = new Cube($coordinate2, $side2);

        return $cube1->getIntersectionVolume($cube2);
		

    }
}

class TestCubeService
{
	public static function testCubeIntersectionVolume()
    {
        $cubeService = new CubeService();
		/* The given coordinates are from the center of the cube, so we need to shift them back as they should be */
	    /* to do this for x for example, it is xreal = x- side/2;  */
        $coordinates1 = '10,10,0';
        $side1 = '5';
		
        $coordinates2 = '9, 9, 0';
        $side2 = '2';

        $volume = $cubeService->calculateIntersectionVolume(
            $coordinates1,
            (int) $side1,
            $coordinates2,
            (int) $side2
        );
       
		
		if ($volume !=0 ) {
			print ("The cubes intersect and the intersection volume is ". $volume ." cubic units.");
		}
		else { print "Cubes don't intersect!!!";
		}
    }
	
	
	
	
}
   TestCubeService::testCubeIntersectionVolume(); 
?>
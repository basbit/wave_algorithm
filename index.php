<?php
declare(strict_types=1);

/**
 * Interface ICave
 */
interface ICave {
	/**
	 * @return int
	 */
	public function getX(): int;

	/**
	 * @param int $x
	 */
	public function setX(int $x): void;

	/**
	 * @return int
	 */
	public function getY(): int;

	/**
	 * @param int $y
	 */
	public function setY(int $y): void;

	/**
	 * @return bool
	 */
	public function isEnter(): bool;

	/**
	 * @param bool $isEnter
	 */
	public function setIsEnter(bool $isEnter): void;

	/**
	 * @return bool
	 */
	public function isExit(): bool;

	/**
	 * @param bool $isExit
	 */
	public function setIsExit(bool $isExit): void;
}

/**
 * Interface IMaze
 */
interface IMaze {
	/**
	 * @return Cave[]
	 */
	public function getCaves(): array;

	/**
	 * @param Cave $cave
	 */
	public function addCave(Cave $cave): void;
}

/**
 * Interface ISearchPath
 */
interface ISearchPathInMaze {
	/**
	 * @param Maze $maze
	 */
	public function setMaze(Maze $maze): void;

	/**
	 *
	 */
	public function findPath(): void;
	/**
	 * @return array
	 */
	public function getPath(): array;
}

/**
 * Interface IMazePrinter
 */
interface IMazePrinter{
	/**
	 * @param IMaze $maze
	 *
	 * @return IMazePrinter
	 */
	public function setMaze(IMaze $maze): IMazePrinter;

	/**
	 *
	 */
	public function print(): void;

	/**
	 * @param string $borderColor
	 *
	 * @return IMazePrinter
	 */
	public function setBorderColor(string $borderColor): IMazePrinter;

	/**
	 * @param string $enterColor
	 *
	 * @return IMazePrinter
	 */
	public function setEnterColor(string $enterColor): IMazePrinter;

	/**
	 * @param string $exitColor
	 *
	 * @return IMazePrinter
	 */
	public function setExitColor(string $exitColor): IMazePrinter;

	/**
	 * @param string $pathColor
	 *
	 * @return IMazePrinter
	 */
	public function setPathColor(string $pathColor): IMazePrinter;

	/**
	 * @param int $width
	 *
	 * @return IMazePrinter
	 */
	public function setWidth(int $width): IMazePrinter;

	/**
	 * @param int $height
	 *
	 * @return MazePrinter
	 */
	public function setHeight(int $height): IMazePrinter;
}

/**
 * Class Cave
 */
class Cave implements ICave
{
	const CAVE_SIZE_H   = 10;
	const CAVE_SIZE_V   = 10;

	/** @var int */
	private int $x;
	/** @var int */
	private int $y;
	/** @var bool */
	private bool $isEnter = false;
	/** @var bool */
	private bool $isExit = false;
	/** @var bool */
	private bool $up = true;
	/** @var bool */
	private bool $right = true;
	/** @var bool */
	private bool $down = true;
	/** @var bool */
	private bool $left = true;
	/** @var Cave[] */
	private ?array $neighbors = [];
	/** @var int */
	private ?int $weight = null;

	/**
	 * @return int
	 */
	public function getX(): int
	{
		return $this->x;
	}

	/**
	 * @param int $x
	 */
	public function setX(int $x): void
	{
		$this->x = $x;
	}

	/**
	 * @return int
	 */
	public function getY(): int
	{
		return $this->y;
	}

	/**
	 * @param int $y
	 */
	public function setY(int $y): void
	{
		$this->y = $y;
	}

	/**
	 * @return bool
	 */
	public function isEnter(): bool
	{
		return $this->isEnter;
	}

	/**
	 * @param bool $isEnter
	 */
	public function setIsEnter(bool $isEnter): void
	{
		$this->setWeight(0);
		$this->isEnter = $isEnter;
	}

	/**
	 * @return bool
	 */
	public function isExit(): bool
	{
		return $this->isExit;
	}

	/**
	 * @param bool $isExit
	 */
	public function setIsExit(bool $isExit): void
	{
		$this->isExit = $isExit;
	}

	/**
	 * @return bool
	 */
	public function isUp(): bool
	{
		return $this->up;
	}

	/**
	 * @param bool $up
	 */
	public function setUp(bool $up): void
	{
		$this->up = $up;
	}

	/**
	 * @return bool
	 */
	public function isRight(): bool
	{
		return $this->right;
	}

	/**
	 * @param bool $right
	 */
	public function setRight(bool $right): void
	{
		$this->right = $right;
	}

	/**
	 * @return bool
	 */
	public function isDown(): bool
	{
		return $this->down;
	}

	/**
	 * @param bool $down
	 */
	public function setDown(bool $down): void
	{
		$this->down = $down;
	}

	/**
	 * @return bool
	 */
	public function isLeft(): bool
	{
		return $this->left;
	}

	/**
	 * @param bool $left
	 */
	public function setLeft(bool $left): void
	{
		$this->left = $left;
	}

	/**
	 *
	 */
	public function getNeighbors(): ?array
	{
		return $this->neighbors;
	}

	/**
	 * @return int
	 */
	public function getWeight(): ?int
	{
		return $this->weight;
	}

	/**
	 * @param int $weight
	 */
	public function setWeight(int $weight): void
	{
		$this->weight = $weight;
	}

	/**
	 * @param string $dir
	 * @param bool   $value
	 * @param int    $maxCountByX
	 * @param int    $maxCountBY
	 *
	 * @return void
	 */
	public function setDirection(string $dir, bool $value, int $maxCountByX, int $maxCountBY): void
	{
		switch ($dir) {
			case 'u':
				$this->setUp($value);
				if (!$value && ($this->getY() - 1) >= 0) {
					$this->neighbors[] = $this->getX().'_'.($this->getY() - 1);
				}
				break;
			case 'r':
				$this->setRight($value);
				if (!$value && ($this->getX() + 1) <= $maxCountByX) {
					$this->neighbors[] = ($this->getX() + 1).'_'.$this->getY();
				}
				break;
			case 'd':
				$this->setDown($value);
				if (!$value && ($this->getY() + 1) <= $maxCountBY) {
					$this->neighbors[] = $this->getX().'_'.($this->getY() + 1);
				}
				break;
			case 'l':
				$this->setLeft($value);
				if (!$value && ($this->getX() - 1) >= 0) {
					$this->neighbors[] = ($this->getX() - 1).'_'.$this->getY();
				}
				break;
		}
	}
}

/**
 * Class Maze
 */
class Maze implements IMaze
{
	/** @var ICave[] */
	private array $caves;
	/** @var string */
	private string $enterCoords;
	/** @var string */
	private string $exitCoords;
	/** @var array */
	private array $path;

	/**
	 * @return ICave[]
	 */
	public function getCaves(): array
	{
		return $this->caves;
	}

	/**
	 * @param ICave $cave
	 */
	public function addCave(ICave $cave): void
	{
		$this->caves[$cave->getX().'_'.$cave->getY()] = $cave;
	}

	/**
	 * @return string
	 */
	public function getEnterCoords(): string
	{
		return $this->enterCoords;
	}

	/**
	 * @param int $x
	 * @param int $y
	 */
	public function setEnter(int $x, int $y): void
	{
		$this->enterCoords = $x.'_'.$y;
	}

	/**
	 * @return string
	 */
	public function getExitCoords(): string
	{
		return $this->exitCoords;
	}

	/**
	 * @param int $x
	 * @param int $y
	 */
	public function setExit(int $x, int $y): void
	{
		$this->exitCoords = $x.'_'.$y;
	}

	/**
	 * @return array
	 */
	public function getPath(): array
	{
		return $this->path;
	}

	/**
	 * @param array $path
	 */
	public function setPath(array $path): void
	{
		$this->path = $path;
	}
}

/**
 * Class SearchPathInMaze
 */
class SearchPathInMaze implements ISearchPathInMaze
{
	/** @var IMaze */
	private IMaze $maze;
	/** @var array */
	private array $path = [];

	/**
	 * @param IMaze $maze
	 */
	public function setMaze(IMaze $maze): void
	{
		$this->maze = $maze;
	}

	/**
	 *
	 */
	public function findPath(): void
	{
		$this->wave();

		$this->path[$this->maze->getExitCoords()] = $this->maze->getExitCoords();
		$finishCave = $this->maze->getCaves()[$this->maze->getExitCoords()];

		$this->getNextCaveRecursion($finishCave);
	}

	/**
	 *
	 */
	private function wave(): void
	{
		$iter      = 0;
		$allCaves  = $this->maze->getCaves();
		$startCave = $allCaves[$this->maze->getEnterCoords()];
		$startCave->setWeight($iter);
		$this->waveRecursion([$startCave], $iter);
	}

	/**
	 * @param array $waveCaves
	 * @param int   $iter
	 *
	 * @return bool
	 */
	private function waveRecursion(array $waveCaves, int $iter): bool
	{
		$newWaveCaves = [];
		/** @var Cave $cave */
		foreach ( $waveCaves as $cave ) {
			foreach ( $cave->getNeighbors() as $caveCoords ) {
				$neighborCave = $this->maze->getCaves()[$caveCoords];
				if ($neighborCave->getWeight()) {
					continue;
				}

				$neighborCave->setWeight($iter + 1);
				$newWaveCaves[] = $this->maze->getCaves()[$caveCoords];

				if ($this->maze->getCaves()[$this->maze->getExitCoords()]->getWeight()) {
					break;
				}
			}

			if ($this->maze->getCaves()[$this->maze->getExitCoords()]->getWeight()) {
				break;
			}
		}

		$iter++;
		if ($this->maze->getCaves()[$this->maze->getExitCoords()]->getWeight()) {
			return true;
		}
		else {
			return $this->waveRecursion($newWaveCaves, $iter);
		}
	}

	/**
	 * @param ICave $finishCave
	 *
	 * @return bool
	 */
	private function getNextCaveRecursion(ICave $finishCave): bool
	{
		foreach ( $finishCave->getNeighbors() as $caveCoords ) {
			$neighborCave = $this->maze->getCaves()[$caveCoords];
			if ($finishCave->getWeight() - $neighborCave->getWeight() == 1 || $neighborCave->isEnter()) {
				$this->path[$caveCoords] = $caveCoords;
				break;
			}
		}

		if (empty($neighborCave)) {
			return false;
		}
		elseif ($neighborCave->isEnter()) {
			return true;
		}
		else {
			return $this->getNextCaveRecursion($neighborCave);
		}
	}

	/**
	 * @return array
	 */
	public function getPath(): array
	{
		return $this->path;
	}
}

/**
 * Class MazePrinter
 */
class MazePrinter implements IMazePrinter
{
	/** @var IMaze  */
	private IMaze $maze;
	/** @var int  */
	private int $width;
	/** @var int  */
	private int $height;
	/** @var string */
	private string $borderColor;
	/** @var string */
	private string $enterColor;
	/** @var string */
	private string $exitColor;
	/** @var string */
	private string $pathColor;
	/** @var string  */
	private string $imgFolder;

	/**
	 * @param IMaze $maze
	 *
	 * @return IMazePrinter
	 */
	public function setMaze(IMaze $maze): IMazePrinter
	{
		$this->maze = $maze;
		return $this;
	}

	/**
	 * @param string $imgFolder
	 *
	 * @return IMazePrinter
	 */
	public function setFolder(string $imgFolder): IMazePrinter {
		$this->imgFolder = $imgFolder;

		return $this;
	}
	/**
	 * @throws ImagickException
	 */
	public function print(): void
	{
		$image = new Imagick();
		$draw = new ImagickDraw();
		$image->newImage($this->width, $this->height, "#ffffff");
		$caves = $this->maze->getCaves();
		array_multisort(array_keys($caves), SORT_NATURAL, $caves);

		foreach ($caves as $cave)
		{
			$x = $cave->getX() * Cave::CAVE_SIZE_H;
			$y = $cave->getY() * Cave::CAVE_SIZE_V;

			$this->drawCaveBorders($x, $y, $cave, $draw);
			$this->drawPath($x, $y, $cave, $draw);
			$this->drawEnterAndExit($x, $y, $cave, $draw);
		}

		$image->drawImage($draw);

		$image->writeImage($this->imgFolder . date('YmdHis') . '.png');
	}

	/**
	 * @param int         $x
	 * @param int         $y
	 * @param ICave       $cave
	 * @param ImagickDraw $draw
	 */
	private function drawPath(int $x, int $y, ICave $cave, ImagickDraw $draw): void
	{
		$xOffset = Cave::CAVE_SIZE_H / 2;
		$yOffset = Cave::CAVE_SIZE_V / 2;

		foreach ( $cave->getNeighbors() as $neighborCoords ) {
			if ($this->maze->getPath()[$neighborCoords]) {
				/** @var Cave $nextCave */
				$nextCave = $this->maze->getCaves()[$neighborCoords];
				$nextX = $nextCave->getX() * Cave::CAVE_SIZE_H;
				$nextY = $nextCave->getY() * Cave::CAVE_SIZE_V;
				$draw->setStrokeColor(new ImagickPixel($this->pathColor));
				$draw->line($x + $xOffset, $y + $yOffset, $nextX + $xOffset, $nextY + $yOffset);
			}
		}
	}

	/**
	 * @param int         $x
	 * @param int         $y
	 * @param ICave       $cave
	 * @param ImagickDraw $draw
	 */
	private function drawEnterAndExit(int $x, int $y, ICave $cave, ImagickDraw $draw): void
	{
		if($cave->isEnter() || $cave->isExit()) {
			$color = $cave->isEnter() ? $this->enterColor : $this->exitColor;
			$draw->setStrokeColor(new ImagickPixel('#ffffff'));
			$draw->setFillColor(new ImagickPixel($color));
			$draw->rectangle($x + 1, $y + 1, $x + Cave::CAVE_SIZE_H - 1, $y + Cave::CAVE_SIZE_V - 1);
		}
	}

	/**
	 * @param int         $x
	 * @param int         $y
	 * @param ICave       $cave
	 * @param ImagickDraw $draw
	 */
	private function drawCaveBorders(int $x, int $y, ICave $cave, ImagickDraw $draw): void {
		$borderColor = new ImagickPixel($this->borderColor);

		if($cave->isUp()) {
			$draw->setStrokeColor($borderColor);
			$draw->line($x , $y, $x + Cave::CAVE_SIZE_H, $y);
		}
		if($cave->isRight()) {
			$draw->setStrokeColor($borderColor);
			$draw->line($x + Cave::CAVE_SIZE_H, $y, $x + Cave::CAVE_SIZE_H, $y + Cave::CAVE_SIZE_V);
		}
		if($cave->isDown()) {
			$draw->setStrokeColor($borderColor);
			$draw->line($x, $y + Cave::CAVE_SIZE_V, $x + Cave::CAVE_SIZE_H, $y + Cave::CAVE_SIZE_V);
		}
		if($cave->isLeft()) {
			$draw->setStrokeColor($borderColor);
			$draw->line($x, $y, $x, $y + Cave::CAVE_SIZE_V);
		}
	}

	/**
	 * @param string $borderColor
	 *
	 * @return IMazePrinter
	 */
	public function setBorderColor(string $borderColor): IMazePrinter
	{
		$this->borderColor = $borderColor;

		return $this;
	}

	/**
	 * @param string $enterColor
	 *
	 * @return IMazePrinter
	 */
	public function setEnterColor(string $enterColor): IMazePrinter
	{
		$this->enterColor = $enterColor;

		return $this;
	}

	/**
	 * @param string $exitColor
	 *
	 * @return IMazePrinter
	 */
	public function setExitColor(string $exitColor): IMazePrinter
	{
		$this->exitColor = $exitColor;

		return $this;
	}

	/**
	 * @param string $pathColor
	 *
	 * @return IMazePrinter
	 */
	public function setPathColor(string $pathColor): IMazePrinter
	{
		$this->pathColor = $pathColor;

		return $this;
	}

	/**
	 * @param int $width
	 *
	 * @return IMazePrinter
	 */
	public function setWidth(int $width): IMazePrinter
	{
		$this->width = $width;
		return $this;
	}

	/**
	 * @param int $height
	 *
	 * @return MazePrinter
	 */
	public function setHeight(int $height): IMazePrinter
	{
		$this->height = $height;
		return $this;
	}
}

/**
 * Class Main
 */
class Main
{
	protected const MAZE_IMG           = 'maze_orig.png';
	protected const IMG_FOLDER         = 'images/';
	protected const ENTER_COLOR        = ['r' => 240,
	                                      'g' => 20,
	                                      'b' => 20];
	protected const EXIT_COLOR         = ['r' => 20,
	                                      'g' => 20,
	                                      'b' => 240];
	protected const BOUNDER_COLOR      = ['r' => 200,
	                                      'g' => 200,
	                                      'b' => 200];
	/** @var int */
	private static int $maxCountByX = 0;
	/** @var int */
	private static int $maxCountByY = 0;

	/**
	 * Main constructor.
	 */
	public function __construct()
	{
		try {
			$maze = new Maze();
			self::parseImgToMaze($maze);

			$searcher = new SearchPathInMaze();
			$searcher->setMaze($maze);
			$searcher->findPath();
			$maze->setPath($searcher->getPath());

			$printer = new MazePrinter();
			$printer->setMaze($maze)
			        ->setBorderColor('#000000')
			        ->setEnterColor('#ff0000')
			        ->setExitColor('#0000ff')
			        ->setPathColor('#00ff00')
			        ->setWidth(Cave::CAVE_SIZE_H * self::$maxCountByX)
			        ->setHeight(Cave::CAVE_SIZE_V * self::$maxCountByY)
			        ->setFolder(self::IMG_FOLDER)
			        ->print();
		}
		catch (Exception $ex) {
			print_R($ex->getMessage());
		}
	}

	/**
	 * @param ICave   $cave
	 * @param Imagick $image
	 * @param int     $x
	 * @param int     $y
	 *
	 * @return ICave
	 * @throws ImagickPixelException
	 */
	private static function getCave(ICave $cave, Imagick $image, int $x, int $y): ICave
	{
		$bounders = ['u' => [5, 0],
		             'r' => [10, 5],
		             'd' => [5, 10],
		             'l' => [0, 5]];

		foreach ( $bounders as $key => $bounder ) {
			$pixel = $image->getImagePixelColor($x + $bounder[0], $y + $bounder[1]);
			$color = $pixel->getColor();
			$isClosed = ($color['r'] <= self::BOUNDER_COLOR['r']
			       && $color['g'] <= self::BOUNDER_COLOR['g']
			       && $color['b'] <= self::BOUNDER_COLOR['b']);
			$cave->setDirection($key, $isClosed, self::$maxCountByX, self::$maxCountByY);
		}

		return $cave;
	}

	/**
	 * @param array $color
	 *
	 * @return bool
	 */
	private static function defineEnter(array $color): bool
	{
		return ($color['r'] >= self::ENTER_COLOR['r'] && $color['g'] <= self::ENTER_COLOR['g']
		        && $color['b'] <= self::ENTER_COLOR['b']);
	}

	/**
	 * @param array $color
	 *
	 * @return bool
	 */
	private static function defineExit($color): bool
	{
		return ($color['r'] <= self::EXIT_COLOR['r'] && $color['g'] <= self::EXIT_COLOR['g']
		        && $color['b'] >= self::EXIT_COLOR['b']);
	}

	/**
	 * @param IMaze   $maze
	 * @param Imagick $image
	 * @param int     $x
	 * @param int     $y
	 *
	 * @throws ImagickPixelException
	 */
	public static function defineCave(IMaze $maze, Imagick $image, int $x, int $y) {
		$cave = new Cave();
		$cave->setX($x);
		$cave->setY($y);

		self::getCave($cave, $image, $x * Cave::CAVE_SIZE_H, $y * Cave::CAVE_SIZE_V);

		$pixel = $image->getImagePixelColor($x * Cave::CAVE_SIZE_H + 5, $y * Cave::CAVE_SIZE_V + 5); //get center of cave
		$color = $pixel->getColor();

		$maze->addCave($cave);

		if (self::defineEnter($color)) {
			$cave->setIsEnter(true);
			$maze->setEnter($x, $y);
		}

		if (self::defineExit($color)) {
			$cave->setIsExit(true);
			$maze->setExit($x, $y);
		}
	}

	/**
	 * @return Imagick
	 * @throws Exception
	 */
	public static function getImage(): Imagick {
		try {
			$imageBlob = file_get_contents(self::IMG_FOLDER.self::MAZE_IMG);

			$image     = new Imagick();
			$image->readImageBlob($imageBlob);
		}
		catch (Exception $ex) {
			throw $ex;
		}

		return $image;
	}

	/**
	 * @param IMaze $maze
	 *
	 * @return void
	 * @throws Exception
	 */
	public static function parseImgToMaze(IMaze $maze): void
	{
		try {
			$image = self::getImage();
			$imageInfo         = $image->getImageGeometry();
			self::$maxCountByX = intval($imageInfo['width'] / Cave::CAVE_SIZE_V);
			self::$maxCountByY = intval($imageInfo['height'] / Cave::CAVE_SIZE_H);

			for ( $x = 0; $x <= self::$maxCountByX; $x++ ) {
				for ( $y = 0; $y <= self::$maxCountByY; $y++ ) {
					self::defineCave($maze, $image, $x, $y);
				}
			}
		}
		catch (Exception $ex) {
			throw $ex;
		}
	}
}

new Main();

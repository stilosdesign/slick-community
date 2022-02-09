<?php
declare(strict_types=1);

namespace App\Models;

use Slick\Helpers\DataLayer\DataLayer;
use Slick\Settings\Defines;

/**
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 2.0
 */
class GeralModel extends DataLayer
{
	protected $tablePost;

	public function __construct() 
	{
		parent::__construct();
	}
	
}
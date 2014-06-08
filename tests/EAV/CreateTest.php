<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Test case for EAV
 *
 * @package    kohana-eav
 * @group      eav
 * @group      module.eav
 * @category   Test
 *
 */

class EAV_CreateTest extends Unittest_TestCase {

	
	public function setUp()
	{
		parent::setUp();
		$this->prepare_test_database();
	}
	
	/**
	 * 
	 * @covers EAV::save
	 */
	public function test_eav_save()
	{
		try
		{
			echo "Testing saving Attributes on Parent Set...\n";
			//testing adding atrributes
			$company = EAV::factory('EAV_Company');
			$company->assign_set(1);
			$company->attr('company_name', 'Simple BG Company');
			$company->attr('age', 5);
			$company->save();
			$this->assertSame('Simple BG Company', $company->attr('name')->value);
			$this->assertEquals(5, $company->attr('age')->value);
			//testing set inheritance
			echo "Testing Saving With Child Set...\n";
			$company_with_child_set = EAV::factory('EAV_Company');
			$company_with_child_set->assign_set(2); //child of set 1
			$company_with_child_set->attr('name', 'Child set');
			$company_with_child_set->attr('age', 10);
			$company_with_child_set->attr('partner', $company->id);
			$company_with_child_set->save();
			
			//die(var_dump($company_with_child_set->attr('partner')->value));
			$this->assertSame('Child set', $company_with_child_set->attr('name')->value);
			$this->assertEquals(10, $company_with_child_set->attr('age')->value);
			
			$company_with_child_set_partner = $company_with_child_set->attr('partner')->value;
			
			$this->assertTrue($company->loaded());
			$this->assertTrue($company_with_child_set_partner->loaded());
			$this->assertEquals($company->id, $company_with_child_set_partner->id);
		}
		catch (Exception $ex)
		{
			$this->fail($ex);
		}
	}
	
	private function prepare_test_database()
	{
		DB::query(Database::DELETE, 'DELETE FROM `eav_companies`;')->execute();
		DB::query(Database::UPDATE, 'ALTER TABLE `eav_companies` AUTO_INCREMENT = 1')->execute();
		DB::query(Database::UPDATE, 'ALTER TABLE `eav_company_attribute_value_int` AUTO_INCREMENT = 1')->execute();
		DB::query(Database::UPDATE, 'ALTER TABLE `eav_company_attribute_value_partner` AUTO_INCREMENT = 1')->execute();
		DB::query(Database::UPDATE, 'ALTER TABLE `eav_company_attribute_value_varchar` AUTO_INCREMENT = 1')->execute();

	}
}
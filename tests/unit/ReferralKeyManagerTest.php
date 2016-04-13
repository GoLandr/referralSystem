<?php

namespace tests\unit;

use App\Managers\ReferralKeyManager;
use \Mockery as m;

class ReferralKeyManagerTest extends \TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * Call check method with not existent key
     */
    public function testCheckIfReferralKeyNotExists()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->count')->once()->andReturn(0);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(false, $referralKeyManager->check('nonExistentKey'));
    }

    /**
     * Call check method with existent key
     */
    public function testCheckIfReferralKeyExists()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->count')->once()->andReturn(1);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(true, $referralKeyManager->check('existingKey'));
    }

    /**
     * Try to find ID of first user by referral key
     */
    public function testFindExistingUserIdByReferralKey()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->value')->once()->andReturn(1);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(1, $referralKeyManager->findUserIdByReferralKey('referralKeyOfFirstUser'));
    }

    /**
     * Try to find user by non existent key
     * We should receive null
     */
    public function testFindNonExistentUserIdByReferralKey()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->value')->once()->andReturn(null);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(null, $referralKeyManager->findUserIdByReferralKey('nonExistentKey'));
    }

    /**
     * Try to generate Referral Key with length 10
     */
    public function testGenerateKeyLength()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->count')->once()->andReturn(0);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(ReferralKeyManager::DEFAULT_LENGTH, strlen($referralKeyManager->generate(ReferralKeyManager::DEFAULT_LENGTH)));
    }

    /**
     * Try to generate Referral Key with length 9 (not default)
     */
    public function testGenerateKeyWithNotDefaultLength()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->count')->once()->andReturn(0);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(
            ReferralKeyManager::DEFAULT_LENGTH + 1,
            strlen($referralKeyManager->generate(ReferralKeyManager::DEFAULT_LENGTH + 1))
        );
    }

    /**
     * Case, when first generated key already exists
     * in DB and it should generate new key
     */
    public function testGenerateKeyTwice()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager');
        $db->shouldReceive('connection->table->where->count')->twice()->andReturn(1, 0);

        $referralKeyManager = new ReferralKeyManager($db);
        $this->assertEquals(10, strlen($referralKeyManager->generate(10)));
    }
}
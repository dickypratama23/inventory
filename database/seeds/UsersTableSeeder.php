<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Department;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nik' => '2015150138',
            'name' => 'HASURUNGAN P S',
            'password' => bcrypt('2015150138'),
            'role' => 0
        ]);
        
        User::create([
            'nik' => '2013122105',
            'name' => 'DICKY PRATAMA',
            'password' => bcrypt('2013122105'),
            'role' => 1
        ]);

        User::create([
            'nik' => '2013099100',
            'name' => 'FEBRIEND RONI SIANIPAR',
            'password' => bcrypt('2013099100'),
            'role' => 1
        ]);

        User::create([
            'nik' => '2013095597',
            'name' => 'RISTOMI NOVANTYAS',
            'password' => bcrypt('2013095597'),
            'role' => 1
        ]);

        User::create([
            'nik' => '2013137844',
            'name' => 'SANDY KURNIAWAN',
            'password' => bcrypt('2013137844'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013129493',
            'name' => 'OKY SANDRA',
            'password' => bcrypt('2013129493'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013215286',
            'name' => 'FEBRIO ROSMANTO',
            'password' => bcrypt('2013215286'),
            'role' => 0
        ]);
        
        User::create([
            'nik' => '2013144542',
            'name' => 'SUNARYO',
            'password' => bcrypt('2013144542'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013137842',
            'name' => 'ANGGRA DHIWA GRANDIS',
            'password' => bcrypt('2013137842'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013107924',
            'name' => 'NANDA ALDINO',
            'password' => bcrypt('2013107924'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013103512',
            'name' => 'ARDIAN RISKI SYAPUTRA PULUNGAN',
            'password' => bcrypt('2013103512'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013103508',
            'name' => 'ANDIA PRANATA',
            'password' => bcrypt('2013103508'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013103228',
            'name' => 'ANDREAN FONTADHO',
            'password' => bcrypt('2013103228'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013099099',
            'name' => 'FAJAR BUDI KURNIAWAN',
            'password' => bcrypt('2013099099'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013103514',
            'name' => 'RULLY DIAN SARI',
            'password' => bcrypt('2013103514'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013099096',
            'name' => 'AHMAD SYARIF HARAHAP',
            'password' => bcrypt('2013099096'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013095591',
            'name' => 'MIKKE GUSTIAWAN',
            'password' => bcrypt('2013095591'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013103231',
            'name' => 'INDRA SETIAWAN',
            'password' => bcrypt('2013103231'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013128103',
            'name' => 'PEDI SAPUTRA',
            'password' => bcrypt('2013128103'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013129495',
            'name' => 'ROMAWI',
            'password' => bcrypt('2013129495'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2015047332',
            'name' => 'NAHRUL',
            'password' => bcrypt('2015047332'),
            'role' => 0
        ]);

        User::create([
            'nik' => '2013095596',
            'name' => 'RICCY YACOB',
            'password' => bcrypt('2013095596'),
            'role' => 0
        ]);
    }
}

<?php
/**
 * Description of Car
 *
 * @author Tomasz
 */
class Car {
    protected $id;
    protected $name;
    protected $acceleration;
    protected $max_speed;
    protected $horsepower;
    protected $capacity;
    protected $playerid;
    protected $opened = 0;
    
    public function __construct($id){
        $carModel = $this->getCarModel($id);
        foreach($carModel as $skill=>$value){
            
            if($skill=='id'){
                continue;
            }
            $this->{$skill} = $value;
        }
        $this->id = $id;
        
    }
    
    
    public function isOpened(){
        if($this->opened==1){
            return true;
        }
        return false;
    }
    
    public function setOpened(){
        $this->opened = 1;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getAcceleration(){
        return $this->acceleration;
    }
    
    public function getMaxSpeed(){
        return $this->max_speed;
    }
    
    public function getHorsePower(){
        return $this->horsepower;
    }
    
    
    public function getPhoto(){
        return $this->photo;
    }
    
    public function getCapacity(){
        return $this->capacity;
    }
    
    public function setTable($tableId){
        $this->table = $tableId;
    }
    
    public function getTable(){
        if(isset($this->table))
            return $this->table;
        return false;
    }
    
    public function removeTable(){
        unset($this->table);
    }
    
    public function getCarModel($id){
        $car_models = array(
	array( // row #0
		'id' => 1,
		'name' => 'BMW 318is',
		'capacity' => 1895,
		'horsepower' => 138,
		'max_speed' => 212,
		'acceleration' => 9.70,
		'photo' => 'bmw318is.png',
	),
	array( // row #1
		'id' => 2,
		'name' => 'Nissan Micra Sport',
		'capacity' => 1598,
		'horsepower' => 108,
		'max_speed' => 183,
		'acceleration' => 9.80,
		'photo' => 'nissan_micra.jpg',
	),
	array( // row #2
		'id' => 3,
		'name' => 'Triumph TR7',
		'capacity' => 1998,
		'horsepower' => 105,
		'max_speed' => 175,
		'acceleration' => 9.80,
		'photo' => 'triumph_tr7.jpg',
	),
	array( // row #3
		'id' => 4,
		'name' => 'Alfa Romeo Junior',
		'capacity' => 1300,
		'horsepower' => 136,
		'max_speed' => 197,
		'acceleration' => 10.30,
		'photo' => 'alfa_romeo_junior.png',
	),
	array( // row #4
		'id' => 5,
		'name' => 'Renault Clio III',
		'capacity' => 1598,
		'horsepower' => 110,
		'max_speed' => 190,
		'acceleration' => 10.20,
		'photo' => 'renault_clio_3.jpg',
	),
	array( // row #5
		'id' => 6,
		'name' => 'Mitsubishi Lancer Evo IX GT340',
		'capacity' => 1997,
		'horsepower' => 305,
		'max_speed' => 250,
		'acceleration' => 5.20,
		'photo' => 'mitsubishi_lancer_evo_ix.jpg',
	),
	array( // row #6
		'id' => 7,
		'name' => 'Ford Fiesta R5',
		'capacity' => 1597,
		'horsepower' => 280,
		'max_speed' => 180,
		'acceleration' => 8.10,
		'photo' => 'ford_fiesta_r5.jpg',
	),
	array( // row #7
		'id' => 8,
		'name' => 'Peugeot 208 VTi',
		'capacity' => 1199,
		'horsepower' => 120,
		'max_speed' => 178,
		'acceleration' => 12.20,
		'photo' => 'peugot208_vti.jpg',
	),
	array( // row #8
		'id' => 9,
		'name' => 'Fiat Cinquecento Sport',
		'capacity' => 1109,
		'horsepower' => 54,
		'max_speed' => 150,
		'acceleration' => 13.80,
		'photo' => 'cinquecento.jpg',
	),
	array( // row #9
		'id' => 10,
		'name' => 'Suzuki Swift Sport',
		'capacity' => 1568,
		'horsepower' => 136,
		'max_speed' => 195,
		'acceleration' => 8.70,
		'photo' => 'suzuki_swift.JPG',
	),
	array( // row #10
		'id' => 11,
		'name' => 'Subaru Impreza WRX STI 2008',
		'capacity' => 2457,
		'horsepower' => 297,
		'max_speed' => 243,
		'acceleration' => 4.80,
		'photo' => 'subaru_wrx_sti_2008.jpg',
	),
	array( // row #11
		'id' => 12,
		'name' => 'Citroen Xsara VTS',
		'capacity' => 1998,
		'horsepower' => 167,
		'max_speed' => 220,
		'acceleration' => 8.00,
		'photo' => 'citroen_xsara_vts.jpg',
	),
	array( // row #12
		'id' => 13,
		'name' => 'Mazda 323 GTR',
		'capacity' => 1840,
		'horsepower' => 182,
		'max_speed' => 221,
		'acceleration' => 7.00,
		'photo' => 'mazda323_gtr.jpg',
	),
	array( // row #13
		'id' => 14,
		'name' => 'FSO Polonez',
		'capacity' => 1600,
		'horsepower' => 130,
		'max_speed' => 180,
		'acceleration' => 8.10,
		'photo' => 'fso_polonez.JPG',
	),
	array( // row #14
		'id' => 15,
		'name' => 'Honda Civic Turbo Type R',
		'capacity' => 1998,
		'horsepower' => 306,
		'max_speed' => 268,
		'acceleration' => 5.70,
		'photo' => 'honca_civic_type_r.jpg',
	),
	array( // row #15
		'id' => 16,
		'name' => 'Toyota Celica GT-Four ST205',
		'capacity' => 1998,
		'horsepower' => 239,
		'max_speed' => 246,
		'acceleration' => 5.90,
		'photo' => 'toyota_celica_gt_four.jpg',
	),
	array( // row #16
		'id' => 17,
		'name' => 'Proton Satria Neo Sport',
		'capacity' => 1589,
		'horsepower' => 111,
		'max_speed' => 189,
		'acceleration' => 11.50,
		'photo' => 'pro_satria_neo.png',
	),
	array( // row #17
		'id' => 18,
		'name' => 'Ford Focus RS',
		'capacity' => 1988,
		'horsepower' => 215,
		'max_speed' => 232,
		'acceleration' => 6.70,
		'photo' => 'ford_focus_rs_I.jpg',
	),
	array( // row #18
		'id' => 19,
		'name' => 'Ford Focus WRC',
		'capacity' => 1991,
		'horsepower' => 300,
		'max_speed' => 230,
		'acceleration' => 4.20,
		'photo' => 'ford_focus_wrc.jpg',
	),
	array( // row #19
		'id' => 20,
		'name' => 'Hyundai Accent ',
		'capacity' => 1600,
		'horsepower' => 137,
		'max_speed' => 194,
		'acceleration' => 8.20,
		'photo' => 'hyundai_accent.jpg',
	),
	array( // row #20
		'id' => 21,
		'name' => 'Skoda Octavia WRC',
		'capacity' => 1998,
		'horsepower' => 300,
		'max_speed' => 230,
		'acceleration' => 4.80,
		'photo' => 'skoda_octavia_wrc.png',
	),
	array( // row #21
		'id' => 22,
		'name' => 'Skoda Fabia IRC',
		'capacity' => 1398,
		'horsepower' => 177,
		'max_speed' => 223,
		'acceleration' => 7.30,
		'photo' => 'skoda_fabia.png',
	),
	array( // row #22
		'id' => 23,
		'name' => 'Mini Cooper S',
		'capacity' => 1598,
		'horsepower' => 208,
		'max_speed' => 210,
		'acceleration' => 6.80,
		'photo' => 'mini_cooper_s.png',
	),
	array( // row #23
		'id' => 24,
		'name' => 'Volkswagen Polo R WRC',
		'capacity' => 1666,
		'horsepower' => 315,
		'max_speed' => 200,
		'acceleration' => 3.90,
		'photo' => 'volkswagen_polo_r.jpg',
	),
	array( // row #24
		'id' => 25,
		'name' => 'Suzuki Ignis',
		'capacity' => 1300,
		'horsepower' => 92,
		'max_speed' => 159,
		'acceleration' => 10.70,
		'photo' => 'suzuki_ignis.jpg',
	),
	array( // row #25
		'id' => 26,
		'name' => 'Ford Puma',
		'capacity' => 1679,
		'horsepower' => 125,
		'max_speed' => 203,
		'acceleration' => 9.20,
		'photo' => 'ford_puma.jpg',
	),
	array( // row #26
		'id' => 27,
		'name' => 'Ford Escort V Cosworth',
		'capacity' => 1993,
		'horsepower' => 224,
		'max_speed' => 232,
		'acceleration' => 6.10,
		'photo' => 'ford_escort_rs_crossworth.png',
	),
	array( // row #27
		'id' => 28,
		'name' => 'Peugeot 207',
		'capacity' => 1998,
		'horsepower' => 280,
		'max_speed' => 199,
		'acceleration' => 11.10,
		'photo' => 'peugeot_207.JPG',
	),
	array( // row #28
		'id' => 39,
		'name' => 'Ford Cortina MKIII',
		'capacity' => 1993,
		'horsepower' => 98,
		'max_speed' => 165,
		'acceleration' => 11.10,
		'photo' => 'ford_cortina_mk3.png',
	),
	array( // row #29
		'id' => 40,
		'name' => 'Mazda 2 Sport',
		'capacity' => 1498,
		'horsepower' => 102,
		'max_speed' => 188,
		'acceleration' => 10.40,
		'photo' => 'mazda2_sport.png',
	),
	array( // row #30
		'id' => 41,
		'name' => 'Talbot Samba Rally',
		'capacity' => 1219,
		'horsepower' => 90,
		'max_speed' => 175,
		'acceleration' => 10.80,
		'photo' => 'talbot_samba.png',
	),
	array( // row #31
		'id' => 42,
		'name' => 'Opel Viva GT',
		'capacity' => 1975,
		'horsepower' => 104,
		'max_speed' => 162,
		'acceleration' => 10.70,
		'photo' => 'opel_viva.jpg',
	),
	array( // row #32
		'id' => 43,
		'name' => 'Dacia Sandero',
		'capacity' => 1598,
		'horsepower' => 104,
		'max_speed' => 180,
		'acceleration' => 11.40,
		'photo' => 'dacia_sandero.png',
	),
);


        return $car_models[$id-1];
    }
    
    
    
    
    
    
}


<?php

class Item {

    private $name;
    private $quantity;
    private $brand;
    private $price;

    function Item($name, $quantity, $brand, $price) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->brand = $brand;
        $this->price = $price;
    }

    function getName() {
        return $this->name;
    }

    function getPrice() {
        return $this->price;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getBrand() {
        return $this->brand;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setBrand($brand) {
        $this->brand = $brand;
    }

    function setPrice($price) {
        $this->price = $price;
    }

}

 

	class user{

		private  $firstName;
		private  $lastName;
		private  $record;
		private  $age;
		private  $address1;
		private  $address2;
		private  $city;
		private  $province;
		private  $appoitments;
		private  $allegies;
		private  $dob;
		private  $gender;
		private  $userId;
		private  $email;

		function user($fName,$lName,$userAge,$streatAddress1,$streatAddress2,$birthday,$userCity,$userProvince,$userGender,$email){
			$this->firstName = $fName;
			$this->lastName = $lName;
			$this->record = new record();
			$this->age = $userAge;
			$this->address1 = $streatAddress1;
			$this->address2 = $streatAddress2;
			$this->appointments = array();
			$this->allegies = array();
			$this->dob = $birthday;
			$this->city = $userCity;
			$this->province = $userProvince;
			$this->gender = $userGender;
			$this->email = $email;
		}

		function addAppointment($appointment){
			$this->appointments[$appointment->getAppointmentNumber()] = $appointment;
		}
		function removeAppointment($appointment){
			//code goes here
		}

		function displayAppintments(){

			foreach ($appointments as $appointment) {
				echo ($appointment->getDate()."/t ".$appointment->getDoctor()."<br>");
			}
		}

		function addNewAllegy($allegy){
			$this->allegies[] = $allegy;
		}

		function removeAllegy($allegy){
			//code goes here
		}

		 function displayAllegies(){
		 	if(empty($allegies)){
		 		echo "Patient has no allegies";
		 	}else{
		 	foreach ($allegies as $allegy) {
		 		echo ($allegy."<br>");
		 	}}
		 }
		 function getFullName(){
		 	$hon = '';
		 	if($this->gender==='female'){
		 		$hon = 'Mrs.';
		 	}else{
		 		$hon = 'Mr.';
		 	}
		 	return ($hon." ".$this->firstName." ".$this->lastName);
		 }
		 function getFirstName(){
		 	return $this->firstName;
		 }
               
		 function getLastName(){
		 	return $this->lastName;
		 }
		 function getStreatAddress1(){
		 	return $this->address1;
		 }
		 function getStreatAddress2(){
		 	return $this->address2;
		 }
		 function getCity(){
		 	return $this->city;
		 }
		 function getProvince(){
		 	return $this->province;
		 }
		 function setFirstName($fName){
		 	$this->firstName = $fName;
		 }function setLastName($lName){
		 	$this->lastName = $lName;
		 }function setCity($City){
		 	$this->city = $City;
		 }function setProvince($Province){
		 	$this->province = $Province;
		 }function setStreatAddress1($SA1){
		 	$this->address1 = $SA1;
		 }function setStreatAddress2($SA2){
		 	$this->address2 = $SA2;
		 }
                 function setDOB($dob){
		 	$this->dob = $dob;
		 }
		 function getGender(){
		 	return $this->gender;
		 }
		 function getDob(){
		 	return $this->dob;
		 }
		 function getAge(){
		 	return $this->age;
		 }
		 function getRecord(){
		 	return $this->record;
		 }
		 function addNewReport($report){
		 	$recordTemp = $this->record;
		 	$recordTemp->addReport($report);
		 	$this->record = $recordTemp;

		 }
		 function setUserId($userId){
		 	$this->userId = $userId;
		 }function getUserId(){
		 	return $this->userId;
		 }
		  function getEmail(){
		 	return $this->email;
		 }function setEmail($email){
		 	$this->email =$email;
		 }




	}

	class doctor{

		private  $firstName;
		private  $lastName;
		private  $age;
		private  $address1;
		private  $address2;
		private  $city;
		private  $province;
		private  $dob;
		private  $workingHospital;
		private  $availableTime;
		private  $doctorId;
		private  $speciality;
		private  $gender;
		private  $fee;

		function doctor($fName,$lName,$doctorAge,$streetAddress1,$streetAddress2,$birthday,$doctorCity,$doctorProvince,$Hospital,$DoctorSpeciality,$Gender){
			$this->firstName = $fName;
			$this->lastName = $lName;
			$this->age = $doctorAge;
			$this->address1 = $streatAddress1;
			$this->address2 = $streatAddress2;
			$this->dob = $birthday;
			$this->city = $doctorCity;
			$this->province = $doctorProvince;
			$this->workingHospital = $Hospital;
			$this->availableTime = array();
			$this->speciality = $DoctorSpeciality;
			$this->gender = $Gender;
		}

		function setId($id){
			$this->doctorId = $id;
		}

		
		function getId(){
			return $this->doctorId;
		}
		
		 function getFullName(){
		 	return ("Dr.".$this->firstName." ".$this->lastName);
		 }
		 function getFirstName(){
		 	return $this->firstName;
		 }
		 function getLastName(){
		 	return $this->lastName;
		 }
                 function getDOB(){
		 	return $this->dob;
		 }
		 function displayDetails($date = ''){
		 	$fullName = $this->getFullName();
		 	$available = '';
		 	if($date === ''){
		 		foreach ($this->availableTime as $d => $time) {
		 			$available .= $time."&nbsp"."<a href='appointment2.php?doctorId={$this->doctorId}&&date={$d}$$time={$time}
		 			'>select</a>"."&nbsp";
		 		}
		 		if($available === ''){
		 			$available .= "<a href='appointment2.php?doctorId={$this->doctorId}&&date={$date}&&time=
		 			'>select</a>";
		 		}

		 	}else{
		 		$available = $this->availvableTime[$date]."&nbsp"."<a href='appointment2.php?doctorId={$this->doctorId}&&date={$date}$$time={$time}'>select</a>"."&nbsp";
		 	}
		 	
		 	$details = "<fieldset><legend>{$fullName}</legend>
		 	<p>Working Hospital : {$this->workingHospital}</p>
		 	<p>Available time: {$available}</p>
		 	</fieldset>";
		 	return $details;
		 }
		 function isAvailable($day){
		 	if(array_key_exists($day, $this->availableTime)){
		 		return true;
		 	}
		 	return false;
		 }

		
		 function getHospital(){
		 	return $this->workingHospital;
		 }
		 function setAvailableTime($day,$startTime,$endTime){
		 	unset($this->availableTime[" "]);
		 	$this->availableTime[$day] = array('startTime'=>$startTime,'endTime'=>$endTime);
		 }
		 function getAvailableTime(){
		 	return $this->availableTime;
		 }
		 function getStreatAddress1(){
		 	return $this->address1;
		 }
		 function getStreatAddress2(){
		 	return $this->address2;
		 }
		 function setStreatAddress1($SA1){
		 	$this->address1 = $SA1;
		 }function setStreatAddress2($SA2){
		 	$this->address2 = $SA2;
		 }
		 function getCity(){
		 	return $this->city;
		 }
		 function getProvince(){
		 	return $this->province;
		 }
		 function setCity($City){
		 	$this->city = $City;
		 }function setProvince($Province){
		 	$this->province = $Province;
		 }
                 function setDOB($dob){
		 	$this->dob = $dob;
		 }
		 function setFirstName($fName){
		 	$this->firstName = $fName;
		 }function setLastName($lName){
		 	$this->lastName = $lName;
		 }
		 function removeTime($day){
		 	unset($this->availableTime[$day]);
		 }function setFee($fee){
		 	 $this->fee = $fee;
		 }function getFee(){
		 	return $this->fee;
		 }




	}class admin{

		private  $firstName;
		private  $lastName;
		private  $age;
		private  $address;
		private  $city;
		private  $province;
		private  $dob;
		private  $position;
		private  $gender;
		private  $id;

		function admin($fName,$lName,$adminAge,$adminAddress,$birthday,$adminCity,$adminProvince,$adminPosition,$Gender){
			$this->firstName = $fName;
			$this->lastName = $lName;
			$this->age = $adminAge;
			$this->address = $adminAddress;
			$this->dob = $birthday;
			$this->city = $adminCity;
			$this->province = $adminProvince;
			$this->position = $adminPosition;
			$this->gender = $Gender;
		}

		

		function setAdminId($id){
			$this->id = $id;
		}
		function getId(){
			return $this->id;
		}
		
		 function getFullName(){
		 	return ($this->firstName." ".$this->lastName);
		 }
		 function getFirstName(){
		 	return $this->firstName."<br>";
		 }
		 function getLastName(){
		 	return $this->lastName."<br>";
		 }

		 function registerDoctor($fName,$lName,$doctorAge,$doctorAddress,$birthday,$doctorCity,$doctorProvince,$Hospital,$connection,$DoctorSpeciality){

		 	$firstname = mysqli_real_escape_string($connection,$fName);
		 	$lastname =  mysqli_real_escape_string($connection,$lName);
		 	$address = mysqli_real_escape_string($connection,$doctorAddress);
		 	$city = mysqli_real_escape_string($connection,$doctorCity);
		 	$province = mysqli_real_escape_string($connection,$doctorProvince);
		 	$workingHospital = mysqli_real_escape_string($connection,$Hospital);

		 	$doctor = new doctor($firstname,$lastname,$doctorAge,$address,$birthday,$city,$province,$workingHospital,$DoctorSpeciality);
		 	$serializedDoctor = serialize($doctor);
		 	return $serializedDoctor;
		 }
		 function registerAdmin($fName,$lName,$adminAge,$adminAddress,$birthday,$adminCity,$adminProvince,$adminPosition,$connection,$gender){

		 	$firstname = mysqli_real_escape_string($connection,$fName);
		 	$lastname =  mysqli_real_escape_string($connection,$lName);
		 	$address = mysqli_real_escape_string($connection,$adminAddress);
		 	$city = mysqli_real_escape_string($connection,$adminCity);
		 	$province = mysqli_real_escape_string($connection,$adminProvince);
		 	$position = mysqli_real_escape_string($connection,$adminPosition);

		 	$admin = new admin($firstname,$lastname,$adminAge,$address,$birthday,$city,$province,$position,$gender);
		 	$serializedAdmin = serialize($admin);
		 	return $serializedAdmin;
		 }
		 function registerPharmacyStaff($fName,$lName,$psAge,$psAddress,$birthday,$psCity,$psProvince,$connection,$gender){

		 	$firstname = mysqli_real_escape_string($connection,$fName);
		 	$lastname =  mysqli_real_escape_string($connection,$lName);
		 	$address = mysqli_real_escape_string($connection,$psAddress);
		 	$city = mysqli_real_escape_string($connection,$psCity);
		 	$province = mysqli_real_escape_string($connection,$psProvince);
		 	

		 	$pharmacyStaff = new pharmacyStaff($firstname,$lastname,$psAge,$address,$birthday,$city,$province,$gender);
		 	$serializedPharmacyStaff = serialize($pharmacyStaff);
		 	return $serializedPharmacyStaff;
		 }




	}
	class pharmacyStaff{
		private  $firstName;
		private  $lastName;
		private  $age;
		private  $address;
		private  $city;
		private  $province;
		private  $dob;
		private  $position;
		private  $gender;
		private $id;

		function pharmacyStaff($fName,$lName,$psAge,$psAddress,$birthday,$psCity,$psProvince,$Gender){
			$this->firstName = $fName;
			$this->lastName = $lName;
			$this->age = $psAge;
			$this->address = $psAddress;
			$this->dob = $birthday;
			$this->city = $psCity;
			$this->province = $psProvince;
			$this->gender = $Gender;
		}

		

		function setId($id){
			$this->id = $id;
		}

		
		 function getFullName(){
		 	return ($this->firstName." ".$this->lastName);
		 }
		 function getFirstName(){
		 	return $this->firstName;
		 }
		 function getLastName(){
		 	return $this->lastName;
		 }

		 function getDOB(){
		 	return $this->dob;
		 }
		 function getGender(){
		 	return $this->gender;
		 }

		 function setFirstName($var){
		 	$this->firstName=$var;
		 }
		 function setLastName($var){
		 	$this->lastName=$var;
		 }

		 function setDOB($var){
		 	$this->dob=$var;
		 }
		 function setGender($var){
		 	$this->gender=$var;
		 }

		  function setAge($var){
		 	$this->age=$var;
		 }


		 




	}

	class record{
		private $pastReports = array();
		function addReport($Report){
			$this->pastReports[] = $Report;
		}
		function displayReports($page,$appObj){
			if(empty($this->pastReports)){
				echo "No reports available";
			}else{
				foreach ($this->pastReports as $report) {
					$reportObject = base64_encode(serialize($report));
					echo "<a href='{$page}?date={$report->viewDate()}&&report={$reportObject}&&appObj={$appObj}'>".$report->viewDate()."</a><br>";	
				
				}
			}
		}
		function getPastReports(){
			return $this->pastReports;
		}
	}

	class report{
		private $date;
		private $details;
		private $medicine = array();
		private $time;
		function report($dateCreated,$timeCreated, $medicalDetails,$medicineGiven){
			$this->date = $dateCreated;
			$this->details = $medicalDetails;
			$this->medicine = $medicineGiven;
			$this->time = $timeCreated;
		}
		function viewDate(){
			return $this->date;
		}
		function viewDetails(){
			return $this->details;
		}
		function getMedicine(){
			return $this->medicine;
		}
		function getTime(){
			return $this->time;
		}

	}

	class appointment{
		private  $date;
		private  $day;
		private  $doctor;
		private  $cost;
		private  $startTime;
		private  $endTime;
		private  $appointmentState;
		private  $appointmentNumber;
		private  $user;
		function appointment($appDate,$appDay,$startTime,$endTime,$appDoctor,$appCost,$userObject){
			$this->day = $appDay;
			$this->doctor = $appDoctor;
			$this->cost = $appCost;
			$this->startTime = $startTime;
			$this->endTime = $endTime;
			$this->user = $userObject;
			$this->appointmentState = new unpaidAppointment();
			$this->date = $appDate;
		}
		function getStartTime(){
			return $this->startTime;
		}function getEndTime(){
			return $this->endTime;
		}function getTime(){
			return 'From '.$this->startTime.' to '.$this->endTime;
		}
		function getDay(){
			return $this->day;
		}function getDate(){
			return $this->date;
		}
		function getDoctor(){
			return $this->doctor;
		}
		function pay(){
			$this->appointmentState = new paidAppointment();
		}function active(){
			$this->appointmentState = new activeAppointment();
		}function canceled(){
			$this->appointmentState = new canceledAppointment();
		}
		function getState(){
			return $this->appointmentState->getState();
		}

		function getCost(){
			return $this->cost;
		}
		function getDoctorId(){
			return $this->doctor->getId();
		}
		function getAppointmentNumber(){
			return $this->appointmentNumber;
		}
		function setAppointmentNumber($number){
			$this->appointmentNumber = $number;
		}
		function getUser(){
			return $this->user;
		}
		function setuser($user){
			$this->user = $user;
		}
		function setAppointmentState($state){
			$this->appointmentState = $state;
		}function getAppointmentState(){
			return $this->appointmentState;
		}

	}

	class AppointmentState{
		
	}

	class unpaidAppointment extends AppointmentState{
		private $state = 'unpaid';
		function getState(){
			return $this->state;
		}
	}

	class paidAppointment extends AppointmentState{
		private $state = 'paid';
		function getState(){
			return $this->state;
		}
	}

	class closedAppointment extends AppointmentState{
		private $state = 'closed';
		function getState(){
			return $this->state;
		}
	}
	class activeAppointment extends AppointmentState{
		private $state = 'active';
		function getState(){
			return $this->state;
		}
	}
	class medicineIssuingAppointment extends AppointmentState{
		private $state = 'medicineIssuing';
		function getState(){
			return $this->state;
		}
	}
	
	class canceledAppointment extends AppointmentState{
		private $state = 'cancled';
		function getState(){
			return $this->state;
		}
	}



 ?>

 
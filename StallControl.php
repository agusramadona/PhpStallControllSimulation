<?php
/*
	Docblock Stall Control Function
	-------------------------------------------------
	By: Agus Ramadona
	30 Januari 2021
	Function Details:
	2 Inputs 
	input 1 = Pitch level
	input 2 = Air Speed
	Usage: StallControl(input 1 value, input 2 value)
	-------------------------------------------------
*/

// Normal Pitch Level and Air Speed (Cruising Mode) Constants
define("cruise_pitch",10); // Degree
define("cruise_speed",300);

// Moderate Stall Boundary Constants 
define("modStall_pitch",45); // Degree
define("modSpeed_factor",2);

// Extreme Stall Boundary Constanst
define("extStall_pitch",90); // Degree
define("extSpeed_factor",3);
define("extAngle_trim",-30); // Extreme Stall Angle Trim in Degree
define("extDuration_trim",30); // In seconds

/* --------------------------------------------------------------
	Stall Correction Function
	This function required 2 inputs, Pitch Level and Air Speed
	Nose Up = Positive Pitch Level in degree
	Nose Down = Negative Pitch Level in degree
	--------------------------------------------------------------
*/

function StallCorrection($pitchValue, $speedValue){
	//global $cruise_pitch, $cruise_speed, $modStall_pitch, $modSpeed_factor, $extStall_pitch, $extSpeed_factor, $extAngle_trim, $extDuration_trim;
	# When empty value of pitch level and air speed occured
	if (empty($pitchValue) && empty($speedValue)) {
		$output = '[Warning]: Unable to read Pitch Level and Air Speed, Freezing Pito Tube and Gyro failure could be occured. <br> 
		[Warning]: May be the plane is still on the ground';
		return $output;
	} 
	# When pitch value readed but no air speed 
	elseif (!empty($pitchValue) && $speedValue == 0){
		$output = '[Warning]: The Stall might be occured!!, Pitch Level: '.$pitchValue.' Degree, but unable to detect Air Speed!!<br> [Warning]: Pito Tube could be fail<br>
		[Auto Pilot]: DISENGAGED! | Please Control the flight manually';
		return $output;
	}
	# When Extreme Pitch Level more than 90 degree
	elseif ($pitchValue >= extStall_pitch){
		$corrected_pitch = extAngle_trim;
		$corrected_speed = (extSpeed_factor*$speedValue);
		$output0 = '[Warning]: Extreem Stall Detected!, Pitch Level: '.$pitchValue. ' Degree | Air Speed: '.$speedValue. ' Knots<br>[Correcting the stall in]: '.extDuration_trim.' Seconds | Nose Down to: '. $corrected_pitch.' degree | Air Speed: '.$corrected_speed.' Knots<br>';
		$output1 = $output0.'[Corrected]: Cruising Mode Restored, at: '.cruise_pitch.' Degree | Air Speed: '.cruise_speed. ' Knots';
		return $output1;
	}
	# When Moderate Stall Detected at Pitch Level more than 45 degree
	elseif($pitchValue >= modStall_pitch){
		$corrected_pitch = modStall_pitch;
		$corrected_speed = (modSpeed_factor*$speedValue);
		$output0 = '[Warning]: Moderate Stall Detected!, Pitch Level: '.$pitchValue. ' Degree | Air Speed: '.$speedValue. ' Knots<br>
	[Correcting the stall]: In Progress | Correcting Pitch Level to: '.cruise_pitch.' Degree at Speed: '.$corrected_speed.' Knots<br>';
		$output1 = $output0.'[Corrected]: Cruising Mode Restored, at: '.cruise_pitch.' Degree | Air Speed: '.cruise_speed. ' Knots';
		return $output1;
		
	}
	else {
		$corrected_pitch = modStall_pitch;
		$corrected_speed = (modSpeed_factor*$speedValue);
		$preoutput = '[Current Info: ] Pitch Level: '.$pitchValue.' Degree | Air Speed: '.$speedValue.' Knots <br>';
		$output = $preoutput.'[Status]: Correcting & Maintaining at: '.cruise_pitch. ' degree | Air Speed: '.cruise_speed. ' Knots';
		return $output;
	}
}

# DISPLAY OUTPUT

echo "[PITCH LEVEL STATUS]<br><br>";

# Empty value from sensors
$warningOutput = StallCorrection(null,null);
echo $warningOutput."<br><br>";

# Zero Air Speed
$warningOutput = StallCorrection(47,0);
echo $warningOutput."<br><br>";

# Extreem Stall
$warningOutput = StallCorrection(97,200);
echo $warningOutput."<br><br>";

# Moderate Stall
$warningOutput = StallCorrection(50,600);
echo $warningOutput."<br><br>";

# Other Pitch Level Situation
$warningOutput = StallCorrection(-40,800);
echo $warningOutput."<br><br>";




?>


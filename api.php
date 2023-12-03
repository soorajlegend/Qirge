<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
        $numbers = $_GET['number'] ?? 0;
        function getAudioFile($number) {
            // Set the path where you saved the audio files
            $audioPath = 'http://localhost/qirge/src/audio/Hausa/suraj/';
            
            // Construct the audio file path based on the provided number
            $audioFile = $audioPath . $number . '.mp3';
            
            return $audioFile;
        }


        function HausaTensAndUnit($number) {
            $units = [
                0 => 'sifiri',
                1 => 'daya',
                2 => 'biyu',
                3 => 'uku',
                4 => 'hudu',
                5 => 'biyar',
                6 => 'shida',
                7 => 'bakwai',
                8 => 'takwas',
                9 => 'tara',
                10 => 'goma',
                20 => 'ashirin',
                30 => 'talatin',
                40 => 'arba\'in',
                50 => 'hamsin',
                60 => 'sittin',
                70 => 'saba\'in',
                80 => 'tamanin',
                90 => 'chasa\'in'
            ];
        
            $tens = [
                10 => 'goma',
                20 => 'ashirin',
                30 => 'talatin',
                40 => 'arba\'in',
                50 => 'hamsin',
                60 => 'sittin',
                70 => 'saba\'in',
                80 => 'tamanin',
                90 => 'chasa\'in'
            ];

            if((int)$number === 0){
                return null;
            }
        
            if ($number > 100) {
                return HausaHundreds($number);
            }
        
            if ($number == 100) {
                return 'dari';
            }
        
            if ($number >= 11 && $number <= 19) {
                return 'doma sha ' . $units[$number % 10];
            }
        
            if ($number >= 21) {
                $tensPart = floor($number / 10) * 10;
                $unitPart = $number % 10;
        
                if ($unitPart > 0) {
                    return $tens[$tensPart] . ' da ' . $units[$unitPart];
                } else {
                    return $tens[$tensPart];
                }
            }
        
            return $units[(int)$number];
        }

        function HausaHundreds($number){
            if($number < 100){
                return HausaTensAndUnit($number);
            }

            if($number > 999){
                return HausaThousands($number);
            }

            $numberString = (string)$number;

            $numberofHundreds = $numberString[0] === 1 ? "" : $numberString[0];

            $tensAndUnit = (int)substr($numberString, 1);


            if((int)$tensAndUnit !== 0){
                $tensAndUnit = " da " . HausaTensAndUnit($tensAndUnit);
            }else{
                $tensAndUnit = "";
            }

            $numbersInHausa = HausaTensAndUnit($numberofHundreds);

            return "dari ". $numbersInHausa . $tensAndUnit;
        }

        // function 

        function HausaThousands($number){
            if($number < 1000){
                return HausaHundreds($number);
            }

            if($number > 999999){
                return $number;
            }

            $numberString = (string)$number;

            $theHundreds = (int)substr($numberString, -3);

            $theThousands = (int)substr($numberString, 0, -3);

            if($theHundreds !== 000){
                $numbersInHausa = HausaThousands($theHundreds);
                $connector = !$numbersInHausa ? "" : " da ";
                $hundredTensAndUnit = $connector . HausaTensAndUnit($theHundreds);
            }else{
                $hundredTensAndUnit = "";
            }

            return HausaHundreds($theThousands) . $hundredTensAndUnit;
        }

        $starter = [
            2 => "dubu",
            3 => "miliyan",
            4 => "biliyan",
            5 => "tiriliyan",
        ];
        

        $chunks = str_split(strrev($numbers), 3);

        $result =  "";
        $identifiers = [$starter];
        
        for($i = count($chunks) - 1; $i >= 0; $i--){
            $chunk = $chunks[$i];
            $numbersInHausa = HausaThousands(strrev($chunk));
            $nextIndex = $i + 1;
            $identifier = isset($starter[$nextIndex]) && $starter[$nextIndex] !== "" ? $starter[$nextIndex] . " " : "";

            if((int)$chunk === 0){
                $identifier = "";
            }

            $connector =  !$numbersInHausa || $i === count($chunks) - 1 ? $identifier : " da ". $identifier;
            $result .= $connector. $numbersInHausa;
        }

        $words = explode(' ', str_replace("'", '', $result));

        $output = [];

        // Loop through each number and create an audio element for each
        foreach ($words as $number) {
            $audioFile = getAudioFile($number);
            $output[] = array(
                "title" => $number,
                "src" => $audioFile,
            );
        }



        
        // $output = "<style>
        //     .speaker {
        //         width: 100%;
        //         height: 100%;
        //     }
        //     .speaker-btn {
        //         width: 40px;
        //         height: 40px;
        //         border-radius: 50px;
        //         border: none;
        //         background: #eee;
        //         transition: all;
        //         animation-duration: 300ms;
        //         cursor: pointer;
        //         padding: 4px;
        //     }
        //     .speaker-btn:hover {
        //         background: #ccc;
        //     }
        // </style>
        // <script src='http://localhost/qirge/src/js/script.js'></script>
        // <button class='speaker-btn' onclick='playSequence(".json_encode($words).")'>
        //     <img src='http://localhost/qirge/src/assets/speaker.svg' class='speaker' />
        // </button>
        // </section>";
        
        echo json_encode($output);

}

?>

    


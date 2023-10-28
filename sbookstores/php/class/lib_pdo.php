<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/conection.php');
class ClassPDO
{

    function __construct()
    {
    }
	

    static function ExtractDATA($Query,$Fields,$Conection){
        $PDO_tmt       = false;
        $Conection 		= ($Conection) ?  $Conection : Conection();
		
        // if( self::basicEval($Query,$Fields,$Conection) ){		
            try {
                $PDO_tmt = $Conection->prepare($Query);
                $PDO_tmt->execute($Fields);
            } catch (PDOException $e) {
                // die($e->getMessage());
            }
		// }	
        return $PDO_tmt;
    }

	
    static function DCRow($Query,$Fields = null,$Conection = null) {
        $Return  = false;		
        $PDO_tmt = self::ExtractDATA($Query,$Fields,$Conection);
        if($PDO_tmt){
            $Return = $PDO_tmt->fetchObject();
        }
        return $Return;
		
    }

    static function DCRows($Query,$Fields,$Conection) {
 	
        $PDO_tmt = self::ExtractDATA($Query,$Fields,$Conection);
        if($PDO_tmt){
            $Return = [];
            while ($Result = $PDO_tmt->fetchObject() ) {
                $Return[] = $Result;
            }
        }
        return $Return;
    }

    static function DCUpdate($Tabla,$Data,$Where,$Conection,$Parametros) {


		$Set_Data = Format_Sintax($Data);
		$Where_Data = Format_Sintax($Where);
		
		$Set_Data = implode(', ', $Set_Data);
		$Where_Data = implode(' AND ', $Where_Data);

		$Sql = "UPDATE $Tabla SET $Set_Data WHERE $Where_Data ";
		$Query = $Conection->prepare($Sql);

		try {
			$Cont = 0;
			foreach ($Data as $name => &$value) {
				$Cont += 1;			

				if($Parametros["Id_Data_Type".$Cont] == 2){
					$Query->bindParam( ":".$name, $value,PDO::PARAM_STR);						
				}else{
					$Query->bindParam( ":".$name, $value,PDO::PARAM_INT);						
				}	
			}
			
			foreach ($Where as $name => &$value) {
				$Query->bindParam( ":".$name, $value,PDO::PARAM_INT);
			}
			
			$Query->execute();
			$Conection = null;

		} catch (PDOException $e) {
			die($e->getMessage());
			print "MENSAJE BD: " . $e->getMessage() . "</br>";
		}

        return $rsp;
    }

    static function DCInsert($Tabla,$Data, $Conection,$Parametros) {

            foreach ($Data as $key => $value) {
                $Names[] = (string) $key;
				
                $valor = $Conection->quote($value);
                $Values[] = is_int($valor) ? $valor : ":$key";
				
            }
            $Names = implode(', ', $Names);
            $Values = implode(', ', $Values);


            $Sql = "INSERT INTO $Tabla ( $Names ) VALUES( $Values )";
            $Query = $Conection->prepare($Sql);
            try {
                $Cont = 0;
                foreach ($Data as $name => &$value) {
					
					$Cont += 1;
                    if($Parametros["Id_Data_Type".$Cont] == 2){
                        $Query->bindParam( ":".$name, $value,PDO::PARAM_STR);						
					}else{
                        $Query->bindParam( ":".$name, $value,PDO::PARAM_INT);						
					}	               
				
				}
			
                $Query->execute();
                $return['success'] = $Query;
                $return['lastInsertId'] = $Conection->lastInsertId();
                $Conection = null;

            } catch (PDOException $e) {
                die($e->getMessage());
				print "MENSAJE BD: " . $e->getMessage() . "</br>";
            }

        return $return;
    }

    static function DCDelete($Tabla,$Data,$Conection) {

        $return = array('success' => false, 'lastInsertId' => 0);
		
            foreach ($Data as $key => $value) {

                $Valor = $Conection->quote($value);
                $Values[] = is_int($Valor) ? $Valor : "$key = :$key";
            }
			
            $WhereString = implode(' AND ', $Values);
            $Sql = "DELETE FROM $Tabla WHERE $WhereString ";
            $Query = $Conection->prepare($Sql);

            try {

                foreach ($Data as $name => &$value) {
                    $Query->bindParam( ":".$name, $value);
                }

                $Query->execute();
                $return['success'] = $Query;
                $Conection = null;

            } catch (PDOException $e) {
                die($e->getMessage());
				print "MENSAJE BD: " . $e->getMessage() . "</br>";
            }

        return $return;
    }


    static function countrows($Query,$Fields, $Conection) {

        $rsp  = false;
        $PDO_tmt = self::ExtractDATA($Query,$Fields,$Conection);
        try {
            $Result  = $PDO_tmt->fetchAll(PDO::FETCH_ASSOC);
            $Return = count( $Result );
            $Conection = null;

        } catch (PDOException $e) {
            die($e->getMessage());
			print "MENSAJE BD: " . $e->getMessage() . "</br>";
        }
        return $Return;
		
    }

    static function countcolumn($Query,$Fields, $Conection) {

        $PDO_tmt = self::ExtractDATA($Query,$Fields,$Conection);
        try {

            $Result = $PDO_tmt->columnCount();;
            $Conection = null;

        } catch (PDOException $e) {
            die($e->getMessage());
            print "MENSAJE BD: " . $e->getMessage() . "</br>";
        }

        $Return[] = $Result;
        $Return[] = $PDO_tmt;
        return $Return;
    }

    static function DCDrop($Tabla, $Conection) {

        $Sql = "DROP TABLE IF EXISTS $Tabla  ";
        $Query = $Conection->prepare($Sql);
        try {
            $Query->execute();
            $Conection = null;

        } catch (PDOException $e) {
            die($e->getMessage());
            print "MENSAJE BD: " . $e->getMessage() . "</br>";
        }

        $Return[] = $PDO_tmt;
        return $Return;
    }
	

    static function DCExecute($Sql, $Conection) {

        $Query = $Conection->prepare($Sql);

        try {

            $Query->execute();
            $Conection = null;

        } catch (PDOException $e) {
            die($e->getMessage());
            print "Error!: " . $e->getMessage() . "</br>";
        }
    }
}

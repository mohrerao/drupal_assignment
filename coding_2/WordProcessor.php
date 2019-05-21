<?php


class   WordProcessor
{
    private function ProcessTestCase($str, $word, $flag, $offset)
    {
        # handling invalid case where $index is greater than string length
        # or less than 0
        if ($offset > (mb_strlen($str) - 1) or $offset < 0) {
            return "No Worries";
        }

        $res = mb_strpos($str, $word, $offset);
        $flag = mb_strtolower($flag);

        # if flag is n, we can return the index as it is
        # otherwise, check for spaces
        if ($flag == 'n') {
            return $res;
        } else {
            $BeforePass = false;
            $AfterPass = false;

            # check for space before result
            if ($res == 0) {
                $BeforePass = true;
            } else {
                if ($str[$res - 1] == ' ') {
                    $BeforePass = true;
                }
            }

            # check for space after result
            if ($res + strlen($word) >= strlen($str)) {
                $AfterPass = true;
            } else {
                if ($str[$res + strlen($word)] == ' ') {
                    $AfterPass = true;
                }
            }

            if($BeforePass and $AfterPass) {
                return $res;
            }
        }

        return "No Worries";
    }

    public function ProcessForm($data)
    {
        
        // if (empty($data) or !$data['num-cases']) {
        //     return NULL;
        // }
        $res = $this->ProcessTestCase($data['s1'], $data['s2'], $data['c'], $data['i']);

        return $res;
    }
}

$processor = new WordProcessor();
print($processor->ProcessForm($_POST));
USE `bpmspace_sqms_v6`;

-- WARNUNG you must run BEVOR running this script!
-- 1) "20170309_dump_sqms_structure_and_minimumdata_v6.sql"
-- 2) "20170309_dump_sqms_diff_structure_after_v6.sql"
-- 3) "20170309_dump_sqms_diff_minimumdata_after_v6.sql"


SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES';

DELIMITER //
DROP FUNCTION IF EXISTS str_random_lipsum;
//

CREATE FUNCTION str_random_lipsum(p_max_words SMALLINT
                                 ,p_min_words SMALLINT
                                 ,p_start_with_lipsum TINYINT(1)
                                 )
    RETURNS VARCHAR(10000)
    NO SQL
    BEGIN
    /**
    * String function. Returns a random Lorum Ipsum string of nn words
    * <br>
    * %author Ronald Speelman
    * %version 1.0
    * Example usage:
    * SELECT str_random_lipsum(5,NULL,NULL) AS fiveWordsExactly;
    * SELECT str_random_lipsum(10,5,0) AS five-tenWords;
    * SELECT str_random_lipsum(50,10,1) AS startWithLorumIpsum;
    * See more complex examples and a description on www.moinne.com/blog/ronald
    *
    * %param p_max_words         Number: the maximum amount of words, if no
    *                                    min_words are provided this will be the
    *                                    exaxt amount of words in the result
    *                                    Default = 50
    * %param p_min_words         Number: the minimum amount of words in the
    *                                    result, By providing the parameter, you provide a range
    *                                    Default = 0
    * %param p_start_with_lipsum Boolean:if "1" the string will start with
    *                                    'Lorum ipsum dolor sit amet.', Default = 0
    * %return String
    */

        DECLARE v_max_words SMALLINT DEFAULT 50;
        DECLARE v_random_item SMALLINT DEFAULT 0;
        DECLARE v_random_word VARCHAR(25) DEFAULT '';
        DECLARE v_start_with_lipsum TINYINT DEFAULT 0;
        DECLARE v_result VARCHAR(10000) DEFAULT '';
        DECLARE v_iter INT DEFAULT 1;
        DECLARE v_text_lipsum VARCHAR(1500) DEFAULT 'a ac accumsan ad adipiscing aenean aliquam aliquet amet ante aptent arcu at auctor augue bibendum blandit class commodo condimentum congue consectetuer consequat conubia convallis cras cubilia cum curabitur curae; cursus dapibus diam dictum dignissim dis dolor donec dui duis egestas eget eleifend elementum elit enim erat eros est et etiam eu euismod facilisi facilisis fames faucibus felis fermentum feugiat fringilla fusce gravida habitant hendrerit hymenaeos iaculis id imperdiet in inceptos integer interdum ipsum justo lacinia lacus laoreet lectus leo libero ligula litora lobortis lorem luctus maecenas magna magnis malesuada massa mattis mauris metus mi molestie mollis montes morbi mus nam nascetur natoque nec neque netus nibh nisi nisl non nonummy nostra nulla nullam nunc odio orci ornare parturient pede pellentesque penatibus per pharetra phasellus placerat porta porttitor posuere praesent pretium primis proin pulvinar purus quam quis quisque rhoncus ridiculus risus rutrum sagittis sapien scelerisque sed sem semper senectus sit sociis sociosqu sodales sollicitudin suscipit suspendisse taciti tellus tempor tempus tincidunt torquent tortor tristique turpis ullamcorper ultrices ultricies urna ut varius vehicula vel velit venenatis vestibulum vitae vivamus viverra volutpat vulputate';
        DECLARE v_text_lipsum_wordcount INT DEFAULT 180;
        DECLARE v_sentence_wordcount INT DEFAULT 0;
        DECLARE v_sentence_start BOOLEAN DEFAULT 1;
        DECLARE v_sentence_end BOOLEAN DEFAULT 0;
        DECLARE v_sentence_lenght TINYINT DEFAULT 9;

        SET v_max_words := COALESCE(p_max_words, v_max_words);
        SET v_start_with_lipsum := COALESCE(p_start_with_lipsum , v_start_with_lipsum);

        IF p_min_words IS NOT NULL THEN
            SET v_max_words := FLOOR(p_min_words + (RAND() * (v_max_words - p_min_words)));
        END IF;

        IF v_max_words < v_sentence_lenght THEN
            SET v_sentence_lenght := v_max_words;
        END IF;

        IF p_start_with_lipsum = 1 THEN
            SET v_result := CONCAT(v_result,'Lorem ipsum dolor sit amet.');
            SET v_max_words := v_max_words - 5;
        END IF;

        WHILE v_iter <= v_max_words DO
            SET v_random_item := FLOOR(1 + (RAND() * v_text_lipsum_wordcount));
            SET v_random_word := REPLACE(SUBSTRING(SUBSTRING_INDEX(v_text_lipsum, ' ' ,v_random_item),
                                           CHAR_LENGTH(SUBSTRING_INDEX(v_text_lipsum,' ', v_random_item -1)) + 1),
                                           ' ', '');

            SET v_sentence_wordcount := v_sentence_wordcount + 1;
            IF v_sentence_wordcount = v_sentence_lenght THEN
                SET v_sentence_end := 1 ;
            END IF;

            IF v_sentence_start = 1 THEN
                SET v_random_word := CONCAT(UPPER(SUBSTRING(v_random_word, 1, 1))
                                            ,LOWER(SUBSTRING(v_random_word FROM 2)));
                SET v_sentence_start := 0 ;
            END IF;

            IF v_sentence_end = 1 THEN
                IF v_iter <> v_max_words THEN
                    SET v_random_word := CONCAT(v_random_word, '.');
                END IF;
                SET v_sentence_lenght := FLOOR(9 + (RAND() * 7));
                SET v_sentence_end := 0 ;
                SET v_sentence_start := 1 ;
                SET v_sentence_wordcount := 0 ;
            END IF;

            SET v_result := CONCAT(v_result,' ', v_random_word);
            SET v_iter := v_iter + 1;
        END WHILE;

        RETURN TRIM(CONCAT(v_result,'.'));
END;
//
DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;


SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES';

DELIMITER //
DROP FUNCTION IF EXISTS str_random_character;
//

CREATE FUNCTION str_random_character(p_char VARCHAR(1))
    RETURNS VARCHAR(1)
    NO SQL
    BEGIN
    /**
    * String function. Returns random character based on a mask
    * <br>
    * %author Ronald Speelman
    * %version 1.5
    * Example usage:
    * SELECT str_random_character('d') AS digit;
    * SELECT str_random_character('C') AS UPPER;
    * See more examples and a description on www.moinne.com/blog/ronald
    *
    * %param p_pattern String: the pattern describing the random values
    *                          c returns lower-case character [a-z]
    *                          C returns upper-case character [A-Z]
    *                          A returns either upper or lower-case character [a-z A-Z]
    *                          d returns a digit [0-9]
    *                          D returns a digit without a zero [1-9]
    *                          b returns a bit [0-1]
    *                          X returns hexedecimal character [0-F]
    *                          * returns characters, decimals and special characters [a-z A-Z 0-9 !?-_@$#]
    *                          All other characters are taken literally
    * %return VARCHAR(1)
    */

    DECLARE v_result   VARCHAR(1) DEFAULT '';

        CASE p_char
            WHEN BINARY '*' THEN SET v_result := ELT(1 + FLOOR(RAND() * 69),'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                                                                                 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                                                                                 '!','?','-','_','@','$','#',
                                                                                 0,1,2,3,4,5,6,7,8,9);
            WHEN BINARY 'A' THEN SET v_result := ELT(1 + FLOOR(RAND() * 52),'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                                                                                 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            WHEN BINARY 'c' THEN SET v_result := ELT(1 + FLOOR(RAND() * 26),'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
            WHEN BINARY 'C' THEN SET v_result := ELT(1 + FLOOR(RAND() * 26),'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            WHEN BINARY 'd' THEN SET v_result := ELT(1 + FLOOR(RAND() * 10), 0,1,2,3,4,5,6,7,8,9);
            WHEN BINARY 'D' THEN SET v_result := ELT(1 + FLOOR(RAND() * 9), 1,2,3,4,5,6,7,8,9);
            WHEN BINARY 'X' THEN SET v_result := ELT(1 + FLOOR(RAND() * 16), 0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
            WHEN BINARY 'b' THEN SET v_result := ELT(1 + FLOOR(RAND() * 2), 0,1);
            ELSE
                SET v_result := p_char;
        END CASE;

   RETURN v_result;
END;
//
DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;

SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES';

DELIMITER //
DROP FUNCTION IF EXISTS str_random;
//

CREATE FUNCTION str_random(p_pattern VARCHAR(200))
    RETURNS VARCHAR(2000)
    NO SQL
    BEGIN
    /**
    * String function. Returns a random string based on a mask
    * <br>
    * %author Ronald Speelman
    * %version 2.3
    * Example usage:
    * SELECT str_random('dddd CC') AS DutchZipCode;
    * SELECT str_random('d{4} C{2}') AS DutchZipCode;
    * SELECT str_random('*{5}*(4)') AS password;
    * select str_random('Cccc(4)') as name;
    * SELECT str_random('#X{6}') AS htmlColorCode;
    * See more complex examples and a description on www.moinne.com/blog/ronald
    *
    * %param p_pattern String: the pattern describing the random values
    *                          MASKS:
    *                          c returns lower-case character [a-z]
    *                          C returns upper-case character [A-Z]
    *                          A returns either upper or lower-case character [a-z A-Z]
    *                          d returns a digit [0-9]
    *                          D returns a digit without a zero [1-9]
    *                          b returns a bit [0-1]
    *                          X returns hexadecimal character [0-F]
    *                          * returns characters, decimals and special characters [a-z A-Z 0-9 !?-_@$#]
    *                          DIRECTIVES
    *                          "text"      : text is taken literally
    *                          {nn}        : repeat the last mask nn times
    *                          (nn)        : repeat random, but max nn times
    *                          [item|item] : pick a random item from this list, items are separated by a pipe symbol
    *                          All other characters are taken literally
    * %return String
    */

    DECLARE v_iter              SMALLINT DEFAULT 1;
    DECLARE v_char              VARCHAR(1) DEFAULT '';
    DECLARE v_next_char         VARCHAR(1) DEFAULT '';
    DECLARE v_list              VARCHAR(200) DEFAULT '';
    DECLARE v_text              VARCHAR(200) DEFAULT '';
    DECLARE v_result            VARCHAR(2000) DEFAULT '';
    DECLARE v_count             SMALLINT DEFAULT 0;
    DECLARE v_jump_characters   TINYINT DEFAULT 0;
    DECLARE v_end_position      SMALLINT DEFAULT 0;
    DECLARE v_list_count        TINYINT DEFAULT 0;
    DECLARE v_random_item       TINYINT DEFAULT 0;

    WHILE v_iter <= CHAR_LENGTH(p_pattern) DO

        SET v_char := BINARY SUBSTRING(p_pattern,v_iter,1);
        SET v_next_char := BINARY SUBSTRING(p_pattern,(v_iter + 1),1);

        -- check if text is a fixed text
        IF (v_char = '"') THEN
            -- get the text
            SET v_end_position := LOCATE('"', p_pattern, v_iter + 1);
            SET v_text := SUBSTRING(p_pattern,v_iter + 1,(v_end_position - v_iter) - 1);
            -- add the text to the result
            SET v_result := CONCAT(v_result, v_text);
            SET v_iter := v_iter + CHAR_LENGTH(v_text) + 2;
        -- if character has a count specified: repeat it
        ELSEIF (v_next_char = '{') OR (v_next_char = '(') THEN
            -- find out what the count is (max 999)...
            IF (SUBSTRING(p_pattern,(v_iter + 3),1) = '}') OR
               (SUBSTRING(p_pattern,(v_iter + 3),1) = ')') THEN
                SET v_count := SUBSTRING(p_pattern,(v_iter + 2),1);
                SET v_jump_characters := 4;
            ELSEIF (SUBSTRING(p_pattern,(v_iter + 4),1) = '}') OR
                   (SUBSTRING(p_pattern,(v_iter + 4),1) = ')')THEN
                SET v_count := SUBSTRING(p_pattern,(v_iter + 2),2);
                SET v_jump_characters := 5;
            ELSEIF (SUBSTRING(p_pattern,(v_iter + 5),1) = '}') OR
                   (SUBSTRING(p_pattern,(v_iter + 5),1) = ')')THEN
                SET v_count := SUBSTRING(p_pattern,(v_iter + 2),3);
                SET v_jump_characters := 6;
            ELSE
                SET v_count := 0;
                SET v_jump_characters := 3;
            END IF;
            -- if random count: make it random with a max of count
            IF (v_next_char = '(') THEN
                SET v_count := FLOOR((RAND() * v_count));
            END IF;
            -- repeat the characters
            WHILE v_count > 0 DO
                SET v_result := CONCAT(v_result,str_random_character(v_char));
                SET v_count := v_count - 1;
            END WHILE;
            SET v_iter := v_iter + v_jump_characters;
        -- check if there is a list in the pattern
        ELSEIF (v_char = '[') THEN
            -- get the list
            SET v_end_position := LOCATE(']', p_pattern, v_iter + 1);
            SET v_list := SUBSTRING(p_pattern,v_iter + 1,(v_end_position - v_iter) - 1);
            -- find out how many items are in the list, items are seperated by a pipe
            SET v_list_count := (LENGTH(v_list) - LENGTH(REPLACE(v_list, '|', '')) + 1);
            -- pick a random item from the list
            SET v_random_item := FLOOR(1 + (RAND() * v_list_count));
            -- add the item from the list
            SET v_result := CONCAT(v_result,
                                   REPLACE(SUBSTRING(SUBSTRING_INDEX(v_list, '|' ,v_random_item),
                                           CHAR_LENGTH(SUBSTRING_INDEX(v_list,'|', v_random_item -1)) + 1),
                                           '|', '')
                                  );
            SET v_iter := v_iter + CHAR_LENGTH(v_list) + 2;
        -- no directives: just get a random character
        ELSE
            SET v_result := CONCAT(v_result, str_random_character(v_char));
            SET v_iter := v_iter + 1;
        END IF;

   END WHILE;

   RETURN v_result;
END;
//
DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;




DELIMITER //

DROP PROCEDURE IF EXISTS demo_data;

//


CREATE PROCEDURE  demo_data()

begin

 

DECLARE right_answers SMALLINT DEFAULT 4;

DECLARE wrong_answers SMALLINT DEFAULT 4;

DECLARE start_topic SMALLINT DEFAULT 1;

DECLARE max_topic SMALLINT DEFAULT 3;

DECLARE start_role SMALLINT DEFAULT 1;

DECLARE syllbus_per_topic SMALLINT DEFAULT 1;

DECLARE syselem_per_syllabus SMALLINT DEFAULT 1;

DECLARE questions_per_syllabus SMALLINT DEFAULT 1;

DECLARE wrong_answers_per_question SMALLINT DEFAULT 1;

DECLARE right_answers_per_question SMALLINT DEFAULT 1;

DECLARE actLanguage SMALLINT DEFAULT 1;

DECLARE x SMALLINT DEFAULT 0;

DECLARE y SMALLINT DEFAULT 0;

DECLARE z SMALLINT DEFAULT 0;

DECLARE a SMALLINT DEFAULT 0;

DECLARE b SMALLINT DEFAULT 0;

set start_topic = 1;

set max_topic = 3;

set start_role = 2;

set syllbus_per_topic = 3;

set syselem_per_syllabus = 10;

set questions_per_syllabus = 3;

set right_answers_per_question = 4;

set wrong_answers_per_question = 4;

set actLanguage = 1; -- 1 English, 2 Deutsch



while start_topic <= max_topic
do
  while x < syllbus_per_topic 
  do
  	
    INSERT INTO `sqms_syllabus` (`name`, `sqms_state_id`, `version`, `sqms_topic_id`, `owner`, `sqms_language_id`, `description`) VALUES (CONCAT('Syllabus for Topic ', start_topic),'3', '1', start_topic, 'bpmspace', actLanguage, str_random_lipsum(20,30,0));
    set x = x+1;
    SET @syllabusid = LAST_INSERT_ID();
    while y < syselem_per_syllabus
        do 
        INSERT INTO `sqms_syllabus_element` (`severity`, `sqms_syllabus_id`, `name`, `description`) VALUES ('10', @syllabusid, CONCAT('This is a Syllabus_Element in Syllabus ', @syllabusid ,'and Topic ', start_topic) , str_random_lipsum(20,30,0));
        set y = y+1;
        SET @syselemid = LAST_INSERT_ID();
        
    	while z < questions_per_syllabus
			do
            	INSERT INTO `sqms_question` (`sqms_language_id`, `sqms_question_state_id`, `question`, `author`, `version`, `sqms_question_type_id`, sqms_topic_id) VALUES (actLanguage, '3', CONCAT('This is question Nr ', z+1 ,' in Syllabus_Element with ID ', @syselemid, ' in Syllabus with ID ', @syllabusid ,' and Topic with ID ', start_topic), 'TEST author', '1', '1', start_topic);
                SET @questionid = LAST_INSERT_ID();
                INSERT INTO `sqms_syllabus_element_question` (`sqms_question_id`, `sqms_syllabus_element_id`) VALUES (@questionid, @syselemid);
				set z = z+1;
                
                while a < right_answers_per_question
                do
                	INSERT INTO `sqms_answer` (`answer`, `correct`, `sqms_question_id`) VALUES ('This is a correct answer', 1, @questionid);
                    set a = a+1;
                end while;
                set a = 0;
				
				while b < wrong_answers_per_question
                do
                	INSERT INTO `sqms_answer` (`answer`, `correct`, `sqms_question_id`) VALUES ('This is a wrong answer', 0, @questionid);
                    set b = b+1;
                end while;
                set b = 0;
		end while;
		set z = 0;
        
    end while;
	set y = 0;
    
  end while;
  set start_topic = start_topic+1;
  set x = 0;

end while;

END;

//

DELIMITER ;

-- add 2 more roles
INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('3', 'Topic 2 Author');
INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('4', 'Topic 3 Author');

-- add 2 more topics
INSERT INTO `sqms_topic` (`sqms_topic_id`, `name`, `sqms_role_id`) VALUES ('2', 'Topic 2', '3');
INSERT INTO `sqms_topic` (`sqms_topic_id`, `name`, `sqms_role_id`) VALUES ('3', 'Topic 3', '4');

-- -- assigne roles 2 LIAM user 
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('3', '3', '1');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('4', '4', '1');

call demo_data();

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 2 wrong answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 3 wrong answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 1 wrong answers and 1 right answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 2 wrong answers and 2 right answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 4 wrong answers and 4 right answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 4 wrong answers and 4 right answers - unsortet','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);


INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 1 wrong answers and 7 right answers','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

INSERT INTO sqms_question VALUES (NULL,1,1,'This is a question with 1 wrong answers and 7 right answers - unsortet','BPMspace',1,NULL,NULL,NULL,2,1);
SET @lastid = LAST_INSERT_ID();
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'right answer',1,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);
INSERT INTO sqms_answer VALUES (NULL,'wrong answer',0,@lastid);

UPDATE `sqms_syllabus` set name = CONCAT (sqms_syllabus.name, ' (Syllabus_ID=',sqms_syllabus_id,')') where TRUE;

UPDATE `sqms_syllabus_element` set name = CONCAT (sqms_syllabus_element.name, ' (SyllabusElement_ID=',sqms_syllabus_element_id,')') where TRUE;

UPDATE `sqms_question` set question = CONCAT (sqms_question.question, ' (Question_ID=',sqms_question_id,')') where TRUE;

UPDATE `sqms_answer` set answer = CONCAT (sqms_answer.answer, ' (Answere_ID=',sqms_answer_id,')') where TRUE;

UNLOCK TABLES;

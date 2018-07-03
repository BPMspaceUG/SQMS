SET GLOBAL log_bin_trust_function_creators = 1;

DROP function if exists generate_fname;
DELIMITER $$
CREATE FUNCTION generate_fname () RETURNS varchar(255)
BEGIN
	RETURN ELT(FLOOR(1 + (RAND() * (5-1))), "James","Mary","John","Patricia");
END$$

DELIMITER ;

DROP procedure if exists add_html2_fields;
DELIMITER $$
CREATE procedure  add_html2_fields()
BEGIN
	declare n int;
	declare m int;
    declare x int;
    declare html_1 varchar(10000);
    declare html_2 varchar(10000);
    declare html_3 varchar(10000);
    declare html_4 varchar(10000);
    declare html_5 varchar(10000);
    declare html_6 varchar(10000);
    

    
    
    set html_1 := " 
		<ul>
			<li>$1</li>
			<li>$2</li>
			<li>$3</li>
			<li>$4</li>
		</ul>
    ";
    set html_2 := " <img src=\"http://placehold.it/350x150/09f/f00\" alt=\"\" />";
    set html_3 := " 
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing 
			elit. Aenean commodo ligula eget dolor. Aenean massa 
			<strong>strong</strong>. Cum sociis natoque penatibus 
			et magnis dis parturient montes, nascetur ridiculus 
			mus. Donec quam felis, ultricies nec, pellentesque 
			eu, pretium quis, sem. Nulla consequat massa quis 
			enim. Donec pede justo, fringilla vel, aliquet nec, 
			vulputate eget, arcu. In enim justo, rhoncus ut, 
			imperdiet a, venenatis vitae, justo. Nullam dictum 
			felis eu pede <a class=\"external ext\" href=\"#\">link</a> 
			mollis pretium. Integer tincidunt. Cras dapibus. 
			Vivamus elementum semper nisi. Aenean vulputate 
			eleifend tellus. Aenean leo ligula, porttitor eu, 
			consequat vitae, eleifend ac, enim. Aliquam lorem ante, 
			dapibus in, viverra quis, feugiat a, tellus. Phasellus 
			viverra nulla ut metus varius laoreet. Quisque rutrum. 
			Aenean imperdiet. Etiam ultricies nisi vel augue. 
			Curabitur ullamcorper ultricies nisi.</p>
    ";
    set html_4 := " 
    <table class=\"data\">
	  <tr>
		<th>Entry Header 1</th>
		<th>Entry Header 2</th>
		<th>Entry Header 3</th>
		<th>Entry Header 4</th>
	  </tr>
	  <tr>
		<td>Entry First Line 1</td>
		<td>Entry First Line 2</td>
		<td>Entry First Line 3</td>
		<td>Entry First Line 4</td>
	  </tr>
	  <tr>
		<td>Entry Line 1</td>
		<td>Entry Line 2</td>
		<td>Entry Line 3</td>
		<td>Entry Line 4</td>
	  </tr>
	  <tr>
		<td>Entry Last Line 1</td>
		<td>Entry Last Line 2</td>
		<td>Entry Last Line 3</td>
		<td>Entry Last Line 4</td>
	  </tr>
	</table>
    ";
    set html_5 := " 
    	<div class=\"container\">
		  <div class=\"row\">
			<div class=\"col-*-*\"></div>
			<div class=\"col-*-*\"></div>
		  </div>
		  <div class=\"row\">
			<div class=\"col-*-*\"></div>
			<div class=\"col-*-*\"></div>
			<div class=\"col-*-*\"></div>
		  </div>
		  <div class=\"row\">
			...
		  </div>
		</div>
    ";
    set html_6 := " ä Ä ö Ö ü Ü ß";
    
    # answer 
	set n:=0;
	set m:= ((select count(sqms_answer_id) from sqms_answer)/20);
    
	while n < m do
		set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
		UPDATE sqms_answer set answer = concat (answer, html_1) where sqms_answer_id = x;
		set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
        UPDATE sqms_answer set answer = concat (answer, html_2) where sqms_answer_id = x;
        set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
        UPDATE sqms_answer set answer = concat (answer, html_3) where sqms_answer_id = x;
        set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
        UPDATE sqms_answer set answer = concat (answer, html_4) where sqms_answer_id = x;
        set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
        UPDATE sqms_answer set answer = concat (answer, html_5) where sqms_answer_id = x;
        set x := (SELECT sqms_answer_id FROM sqms_answer ORDER BY RAND() LIMIT 1);
        UPDATE sqms_answer set answer = concat (answer, html_6) where sqms_answer_id = x;
		set n := n+1;
	end while;
    
    
    # question
    
	set n:=0;
	set m:= ((select count(sqms_question_id) from sqms_question)/10);
    
    
    	while n < m do
		set x := (SELECT `sqms_question_id` FROM `sqms_question` ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_1) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_2) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_3) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_4) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_5) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
		UPDATE sqms_question set question = concat ( question, html_6) where sqms_question_id = x;
		set x := (SELECT sqms_question_id FROM sqms_question ORDER BY RAND() LIMIT 1);
        
        set n := n+1;
	end while;
    
    
        # syllabus element 
    
	set n:=0;
	set m:= ((select count(`sqms_syllabus_element_id`) from `sqms_syllabus_element`)/10);
    
    
    	while n < m do
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_1) where sqms_syllabus_element_id = x;
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_2) where sqms_syllabus_element_id = x;
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_3) where sqms_syllabus_element_id = x;
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_4) where sqms_syllabus_element_id = x;
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_5) where sqms_syllabus_element_id = x;
		set x := (SELECT `sqms_syllabus_element_id` FROM `sqms_syllabus_element` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus_element` set `description` = concat ( `description`, html_6) where sqms_syllabus_element_id = x;

        
        set n := n+1;
	end while;
    
            # syllabus
    
	set n:=0;
	set m:= ((select count(`sqms_syllabus_id`) from `sqms_syllabus`)/10);
    
    
    while n < m do
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_1) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_2) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_3) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_4) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_5) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
		UPDATE `sqms_syllabus` set `description` = concat ( `description`, html_6) where sqms_syllabus_id = x;
		set x := (SELECT `sqms_syllabus_id` FROM `sqms_syllabus` ORDER BY RAND() LIMIT 1);
                
        set n := n+1;
	end while;
END$$

DELIMITER ;


SET GLOBAL log_bin_trust_function_creators = 0;

SET SQL_SAFE_UPDATES = 0;
UPDATE `sqms_question` SET `question` = concat("Is this a question with the ID [",`sqms_question_id`, "]?");
UPDATE `sqms_question` SET `author`= generate_fname();
UPDATE `sqms_answer` SET `answer` = concat("This answer with id [" ,`sqms_answer_id` , "] is correct. It is associated to question with ID [",sqms_question_id,"]") WHERE `correct` = 1;
UPDATE `sqms_answer` SET `answer` = concat("This answer with id [" ,`sqms_answer_id` , "] is wrong. It is associated to question with ID [",sqms_question_id,"]") WHERE `correct` = 0;
UPDATE `sqms_topic`SET `name` = concat("Topic with ID [",`sqms_topic_id`,"]");
UPDATE `sqms_syllabus_element` SET `name` = concat("Syllabus Element with ID [",`sqms_syllabus_element_id`,"]");


UPDATE `sqms_role` sr,
(   SELECT `name` , `sqms_role_id`
    FROM `sqms_topic` 
) st
SET `sr`.`role_name` = concat("team of authors for ",`st`.`name`)
WHERE `st`.`sqms_role_id` = `sr`.`sqms_role_id`;

UPDATE sqms_syllabus
	INNER JOIN sqms_topic ON sqms_syllabus.sqms_topic_id =
    sqms_topic.sqms_topic_id
  INNER JOIN sqms_language ON sqms_syllabus.sqms_language_id =
    sqms_language.sqms_language_id
SET
`sqms_syllabus`.`name` =  Concat("Syllabus ID [",`sqms_syllabus`.`sqms_syllabus_id`,"] for ",`sqms_topic`.`name`," - level ",`sqms_syllabus`.`sqms_topic_level`," - rank ",`sqms_syllabus`.`sqms_topic_rank`," - ",`sqms_language`.`language_short`);
UPDATE `sqms_syllabus` SET `description` = concat("Description Syllabus with ID [",`sqms_syllabus_id`,"]");
UPDATE `sqms_syllabus` SET `owner`= generate_fname();


UPDATE `sqms_exam_version` EV1
  INNER JOIN sqms_syllabus ON EV1.sqms_syllabus_id =
    sqms_syllabus.sqms_syllabus_id
  INNER JOIN sqms_language ON EV1.sqms_language_id =
    sqms_language.sqms_language_id
SET
sqms_exam_version_name = concat ("Exam Set for ", `sqms_syllabus`.`name`," - ",
  `sqms_language`.`language_short`," set-",
  `EV1`.`sqms_exam_set`," v-",
  `EV1`.`sqms_exam_version`, 
  COALESCE((select "-SAMPLE_SET" from `sqms_exam_version` EV2 where `EV2`.`sqms_exam_version_sample_set` = 1 AND `EV1`.`sqms_exam_version_id` = `EV2`.`sqms_exam_version_id`),''));

UPDATE `sqms_exam_version` EV1
  INNER JOIN sqms_syllabus ON EV1.sqms_syllabus_id =
    sqms_syllabus.sqms_syllabus_id
  INNER JOIN sqms_language ON EV1.sqms_language_id =
    sqms_language.sqms_language_id
SET  
sqms_exam_version_name = concat ("Exam Set with ID [", `sqms_exam_version_id`,"] ",
  `sqms_language`.`language_short`," set-",
  `EV1`.`sqms_exam_set`," v-",
  `EV1`.`sqms_exam_version`, 
  COALESCE((select "-SAMPLE_SET" from `sqms_exam_version` EV2 where `EV2`.`sqms_exam_version_sample_set` = 1 AND `EV1`.`sqms_exam_version_id` = `EV2`.`sqms_exam_version_id`),''))
  where sqms_syllabus_id IS NULL;
  ;


call add_html2_fields();
SET SQL_SAFE_UPDATES = 1;

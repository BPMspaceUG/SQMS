SET GLOBAL log_bin_trust_function_creators=1;
DROP FUNCTION IF EXISTS fnStripTags;
DELIMITER |
CREATE FUNCTION fnStripTags( Dirty varchar(4000) )
RETURNS varchar(4000)
DETERMINISTIC 
BEGIN
  DECLARE iStart, iEnd, iLength int;
    WHILE Locate( '<', Dirty ) > 0 And Locate( '>', Dirty, Locate( '<', Dirty )) > 0 DO
      BEGIN
        SET iStart = Locate( '<', Dirty ), iEnd = Locate( '>', Dirty, Locate('<', Dirty ));
        SET iLength = ( iEnd - iStart) + 1;
        IF iLength > 0 THEN
          BEGIN
            SET Dirty = Insert( Dirty, iStart, iLength, '');
          END;
        END IF;
      END;
    END WHILE;
    RETURN Dirty;
END;
|
DELIMITER ;

DROP VIEW IF EXISTS v_sqms_question_combinator; 
create view v_sqms_question_combinator as
  select 
  
    concat("[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]",sqms_topic.name,"-",sqms_language.language,"-",substring(sqms_question.question, 1, 20))  as name_text_preview,
  
	sqms_question.sqms_question_id as sqms_question_id,
	concat("<p class=\"sqms_question_text\">",fnStripTags(sqms_question.question),"</p>","<p class=\"sqms_question_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]</p>") as question,
    
    (sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct) as maxpoint,
    
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_1.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_1, 
	sqms_answer_1.correct as correct_id_1,
    (sqms_answer_1.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct)) as fraction_id_1, 
    
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_2.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_2, 
	sqms_answer_2.correct as correct_id_2,
    (sqms_answer_2.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct)) as fraction_id_2,
	
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_3.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_3, 
	sqms_answer_3.correct as correct_id_3,
    (sqms_answer_3.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct)) as fraction_id_3,
    
    sqms_topic.sqms_topic_id,
    sqms_language.language,

    
    concat("[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]") as unique_id
	
  from              sqms_answer sqms_answer_1
         inner join sqms_answer sqms_answer_2
         inner join sqms_answer sqms_answer_3
         inner join sqms_question
         inner join sqms_language
         inner join sqms_topic

         
         on         sqms_answer_1.sqms_question_id = sqms_answer_2.sqms_question_id
                and sqms_answer_2.sqms_question_id = sqms_answer_3.sqms_question_id
                and NOT sqms_answer_2.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and NOT sqms_answer_3.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and sqms_answer_3.sqms_answer_id > sqms_answer_2.sqms_answer_id
                and sqms_answer_2.sqms_answer_id > sqms_answer_1.sqms_answer_id
				and sqms_question.sqms_question_id = sqms_answer_1.sqms_question_id
                and sqms_question.sqms_language_id = sqms_language.sqms_language_id
                and sqms_topic.sqms_topic_id = sqms_question.sqms_topic_id

                
  where  sqms_answer_1.correct=1;

 	
DROP VIEW IF EXISTS v_sqms_syllabus_syllabuselement_question; 
create view v_sqms_syllabus_syllabuselement_question as

  SELECT 
	SYLLA.sqms_syllabus_id,
    SYLLA.name AS S_name,
    SYLLA.description AS S_description,
    SYLEL.sqms_syllabus_element_id ,
    SYLEL.element_order ,
	SYLEL.name AS SE_name,
	SYLEL.description AS SE_description,
	SYLEL.severity,
    (round((SYLEL.severity*30/100)*2)/2) as question_count,
	(round((SYLEL.severity*30/100*0.8)*2)/2) as question_count_min,
    (round((SYLEL.severity*30/100*1.2)*2)/2) as question_count_max,

    QUEST.sqms_question_id,
    QUEST.question
    
  FROM 
  sqms_syllabus_element as SYLEL
   
  LEFT JOIN sqms_syllabus as SYLLA ON SYLLA.sqms_syllabus_id = SYLEL.sqms_syllabus_id 
  LEFT JOIN sqms_syllabus_element_question as SYLEL_Q ON SYLEL.sqms_syllabus_element_id = SYLEL_Q.sqms_syllabus_element_id
  LEFT JOIN sqms_question as QUEST ON SYLEL_Q.sqms_question_id = QUEST.sqms_question_id
  
  ORDER BY SYLLA.sqms_syllabus_id, SYLEL.element_order, QUEST.sqms_question_id  ASC;
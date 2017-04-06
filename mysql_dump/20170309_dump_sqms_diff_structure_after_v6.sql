DROP VIEW IF EXISTS v_sqms_question_combinator; 
create view v_sqms_question_combinator as
  select 
	sqms_answer_1.sqms_question_id as sqms_question_id,
	sqms_question.question,
	
    sqms_answer_1.sqms_answer_id as sqms_answer_id_1, 
    sqms_answer_1.answer as sqms_answer_1, 
	    sqms_answer_1.correct as correct_id_1, 
	
    sqms_answer_2.sqms_answer_id as sqms_answer_id_2, 
    sqms_answer_2.answer as sqms_answer_2, 
    sqms_answer_2.correct as correct_id_2, 
	
    sqms_answer_3.sqms_answer_id as sqms_answer_id_3, 
    sqms_answer_3.answer as sqms_answer_3, 
    sqms_answer_3.correct as correct_id_3
	
  from              sqms_answer sqms_answer_1
         inner join sqms_answer sqms_answer_2
         inner join sqms_answer sqms_answer_3
         inner join sqms_question
         on         sqms_answer_1.sqms_question_id = sqms_answer_2.sqms_question_id
                and sqms_answer_2.sqms_question_id = sqms_answer_3.sqms_question_id
                and NOT sqms_answer_2.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and NOT sqms_answer_3.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and sqms_answer_3.sqms_answer_id > sqms_answer_2.sqms_answer_id
                and sqms_answer_2.sqms_answer_id > sqms_answer_1.sqms_answer_id
				and sqms_question.sqms_question_id = sqms_answer_1.sqms_question_id
  where  sqms_answer_1.correct=1;
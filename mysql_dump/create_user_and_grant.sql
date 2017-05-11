CREATE USER 'bpmspace_sqms'@'localhost' IDENTIFIED BY 'PASSWORTinLASTPASS';
CREATE USER 'bpmspace_sqms_RO'@'localhost' IDENTIFIED BY 'PASSWORTinLASTPASS';
GRANT SELECT, INSERT, UPDATE ON `bpmspace_sqms_v6`.* TO 'bpmspace_sqms'@'localhost';
GRANT SELECT ON `bpmspace_sqms_v6`.* TO 'bpmspace_sqms_RO'@'localhost';

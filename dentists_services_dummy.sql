ALTER TABLE dentists 
ADD COLUMN dentist_description TEXT 
DEFAULT 'This dentist is is an experienced dentist specializing in xxxx. She received her dental degree from the National University of Singapore in 2000. She has been practicing dentistry for 20 years and is a member of the Singapore Dental Association.';

ALTER TABLE dentists
ADD COLUMN dentist_image VARCHAR(64)
DEFAULT 'temp.png';

ALTER TABLE services
ADD COLUMN service_description TEXT
DEFAULT 'Service X is a dental service that provides xxxx. Our team of experienced dentists will ensure that you receive the best care possible. We use the latest technology and techniques to provide you with the best possible results.';

ALTER TABLE services
ADD COLUMN service_image VARCHAR(64)
DEFAULT 'temp.png';

UPDATE dentists
SET dentist_image = CASE dentist_name
    WHEN 'Dr Eunice Seng' THEN 'dr_eunice_seng.png'
    WHEN 'Dr Thong Peiyu' THEN 'dr_thongpeiyu.png'
    WHEN 'Dr Ali Abu bin Akau' THEN 'dr_aliabubinakau.png'
    END
WHERE dentist_name IN ('Dr Eunice Seng', 'Dr Thong Peiyu', 'Dr Ali Abu bin Akau');

INSERT INTO services (service_type, service_description, service_image) VALUES
('Dental Restoration', 'A procedure that restores the function and appearance of damaged teeth, including fillings, crowns, and bridges.', 'temp.png'),
('Root Canal/Endodontic Treatment', 'A dental procedure to treat infection at the center of a tooth, often preserving the natural tooth structure.', 'temp.png'),
('Bruxism and Grinding', 'Treatment options for teeth grinding and jaw clenching, which can lead to tooth wear and jaw pain.', 'temp.png'),
('Dental Implants and Oral Surgery', 'Surgical procedures to replace missing teeth with artificial implants and manage oral health issues.', 'temp.png');

UPDATE services
SET service_image = CASE service_id
    WHEN 1 THEN 'general_dentistry.png'
    WHEN 2 THEN 'dental_braces.png'
    WHEN 3 THEN 'scaling_and_polishing.png'
    WHEN 4 THEN 'dental_restoration.png'
    WHEN 5 THEN 'root_canal_treatment.png'
    WHEN 6 THEN 'bruxism_and_grinding.png'
    WHEN 7 THEN 'dental_implant.png'
    END;

UPDATE services
SET service_type = 'Root Canal Treatment'
WHERE service_id = 5;

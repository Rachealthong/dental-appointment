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

--------- start from here ----------------
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

UPDATE `services` SET `service_description` = 'Our general dentistry services include fillings, tooth extraction and custom-fitted mouth guards.\r\n\r\n<h3>Fillings</h3>\r\nFillings can help to replace tooth structure that has been lost to decay or damage. For example, if a small piece of your tooth is chipped, fillings can repair and restore it to a normal function and appearance and stop further decay by providing a barrier against bacteria.\r\n\r\n<h3>Extraction</h3>\r\nIf your teeth are affected by advanced gum disease, deep cracks, or are deeply decayed, it is likely that other treatments cannot preserve your teeth, and we may recommend having them extracted instead.\r\n\r\n' WHERE `services`.`service_id` = 1;
UPDATE services SET service_description = "Braces are an effective orthodontic solution with metal brackets, wires, and modules. In addition to improving oral health, they also function as a cosmetic dentistry procedure by correcting issues such as crooked teeth and improving oral health. <h3>Fees</h3> Braces treatment begins at $4,033, varying with your case’s complexity. A fee of $239.80 (includes GST) will be charged for the initial consultation package where we will collect diagnostic records for an in-depth assessment of your teeth condition. <br><br> The consultation package includes: <ul> <li>Consultations</li> <li>Diagnostics records (4 X-rays, iTero scans, study models, and photos)</li> </ul> Bright Smile Dental provides 4 types of retainers – Vivera, Hawley, clear, and permanent retainers, with our dentists recommending the best type for you." WHERE service_id = 2;
UPDATE services 
SET service_description = "
Scaling and polishing are critical parts of dental cleaning that help maintain oral hygiene by removing stains, plaque, and tartar unreachable by regular brushing. During scaling, specialised dental instruments loosen and remove plaque and tartar. This is followed by polishing, where a spinning brush or rubber cup smooths the teeth’s surface, making it more difficult for plaque to adhere. Regular sessions help detect and manage potential dental issues like gum disease or cavities early, preventing more serious problems.
<br><br>
At Bright Smile Dental, scaling and polishing services start at $65.40, depending on the specific needs of your treatment."

WHERE service_id = 3;

UPDATE services SET service_description = "Restorative dentistry encompasses dental procedures that are focused on repairing or replacing damaged and missing teeth. This can occur due to trauma, decay, cavities and many other dental conditions. <h3>Benefits</h3> Through restorative dentistry, the function of these teeth can be restored and your overall dental health can be improved. It can reduce the likelihood of infections due to damaged teeth and encourage better oral hygiene by preventing the buildup of plaque. By addressing current oral conditions promptly, restorative dentistry can help to prevent further dental diseases or issues from occurring" WHERE service_id = 4;
update services
set service_description = "A root canal treatment is an endodontic procedure used to repair and save a severely infected or badly decayed tooth. It helps to relieve pain and make the tooth healthy.
<br><br>
During a root canal procedure, the infected pulp tissues are removed. After which, the inside of a tooth is disinfected and cleaned before it gets sealed with a filling.
<br><br>
With treatment, the tooth can recover and revert to its normal functions. Otherwise, the tooth may have to be extracted.

"
WHERE service_id = 5;

update services set service_description = "Bruxism is a condition in which you grind, gnash or clench your teeth. If you have bruxism, you may unconsciously clench your teeth when you're awake (awake bruxism) or clench or grind them during sleep (sleep bruxism). <br><br> Sleep bruxism is considered a sleep-related movement disorder. People who clench or grind their teeth (brux) during sleep are more likely to have other sleep disorders, such as snoring and pauses in breathing (sleep apnea). <br><br> Mild bruxism may not require treatment. However, in some people, bruxism can be frequent and severe enough to lead to jaw disorders, headaches, damaged teeth and other problems. <br><br> Because you may have sleep bruxism and be unaware of it until complications develop, it's important to know the signs and symptoms of bruxism and to seek regular dental care." where service_id = 6;
update services set service_description = "Dental implants serve as a durable replacement for lost teeth due to periodontal disease, injury, or other causes. These artificial tooth roots are surgically placed into the jawbone to securely hold dental crown, dental bridge or dentures. <br><br> At Bright Smile Dental, our dental implant solutions range from single tooth replacements to implant-supported bridges and dentures, including metal-free options. Prices range from $3,815 to $7,085 per tooth, tailored to meet your specific dental needs. <h3>Benefits</h3> <ul> <li>Enhances ability to chew</li> <li>Enhances speeches</li> <li>Prevent jawbone loss</li> <li>Comfortable and natural</li> </ul> " where service_id = 7;

UPDATE dentists 
SET dentist_description = "
<h3>Dental Specialist in Orthodontics</h3>
<h3>BDS (Singapore), MDS (Orthodontics) (Singapore), M Orth RCS (Edinburgh, UK)</h3>
Dr Eunice Seng is a Specialist Orthodontist registered with the Singapore Dental Council. She graduated from the National University of Singapore with a record number of 13 academic awards conferred to a graduate, winning prestigious awards like the Most Distinguished Graduate in Dentistry, the Best Clinical Student award, and the University Silver Medal for emerging First in Examination. She was also placed on the Dean’s List every year.
<br><br>
Her interest in braces (orthodontics) led her to pursue a full-time postgraduate program, and she graduated with a Master of Dental Surgery (Orthodontics) degree and a Membership in Orthodontics from the Royal College of Surgeons of Edinburgh. She is also a member of the Association of Orthodontists Singapore. Her research interests in orthodontic (braces) cephalometry were presented at numerous international conferences.
"
WHERE dentist_id = 1;

UPDATE dentists SET dentist_description = " <h3>Dental Specialist in Prosthodontics</h3> <h3>BDS (Singapore)</h3> <h3>MDS (Prosthodontics) (Singapore)</h3> Dr Thong Peiyu graduated with her Bachelor in Dental Surgery degree from the National University of Singapore. She was placed on the Dean’s list and awarded a number of prestigious medals, including the FAC Oehlers Medal, Terrell Silver Medal and the Q&M Dental Surgery Medal for Operative Dentistry. <br><br> Her interest and flair in Prosthodontics led her to pursue her Masters in Dental Surgery (Prosthodontics) at the National University of Singapore. She spent three years in postgraduate training before being accredited as a Specialist in Prosthodontics registered with the Singapore Dental Council. Through the conjoint examination, she also obtained her Membership in Prosthodontics from the Royal College of Surgeons, Edinburgh. She is also a Fellow of the Academy of Medicine, Singapore. <br><br> " WHERE dentist_id = 2;

update dentists
set dentist_description = "
<h3>Dental Specialist in Periodontics</h3>
<h3>BDS (Singapore), MDS (Periodontology) (Singapore), MRD RCS (Edinburgh, UK)</h3>
Dr Ali Abu bin Akau graduated with a Bachelor of Dental Surgery degree from the National University of Singapore. He also received specialist training in periodontology and graduated with a Master of Dental Surgery degree. In the same year, he obtained his fellowship in periodontology from the Royal College of Surgeons of Edinburgh (United Kingdom).
<br><br>
Dr Ali is an accredited specialist in periodontology registered with the Singapore Dental Council. He is a visiting specialist at the Singapore Armed Forces and the National University Hospital. He also teaches the undergraduate and postgraduate students in the National University of Singapore. Besides teaching in the university, Dr Ali also shares his knowledge and skills in organizations like International Dental Academy (IDA) and Singapore Academy of Oral Rehabilitation and Implantology (SAORI). And he speaks frequently in local and overseas conferences.
"
WHERE dentist_id = 3;


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
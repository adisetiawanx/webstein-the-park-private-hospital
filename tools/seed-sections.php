<?php
/**
 * Populate the six pages built from the flexible section template.
 *
 * Visitors, Preparing for your Admission, Post Operative Care, Patient Rights &
 * Responsibilities, Credentialing and Safety and Quality.
 *
 * Credentialing and Safety and Quality are lorem ipsum in the artboards. That
 * placeholder is reproduced rather than invented around — writing plausible
 * copy about a hospital's accreditation would be worse than leaving it obviously
 * unfinished.
 */

function tpph_media( $slug ) {
	$map = get_option( 'tpph_media_map', array() );
	return isset( $map[ $slug ] ) ? (int) $map[ $slug ] : 0;
}

const TPPH_LOREM_1 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

$pages = array();

/* ------------------------------------------------------------------ Visitors */

$pages['for-patients-visitors'] = array(
	'hero_title' => 'For Patients & Visitors',
	'hero_image' => tpph_media( '3-for-patients-visitors-header' ),
	'sections'   => array(
		array(
			'layout'     => 'text',
			'background' => 'white',
			'heading'    => 'Visitors',
			'text'       => 'To help maintain a calm and safe environment for all patients, The Park Private Hospital has guidelines around who can accompany a patient on the day of their procedure, and during their stay. These guidelines are based on patient age, clinical needs and length of stay.',
			'images'     => array(),
		),
		array(
			'layout'     => 'columns',
			'background' => 'cream',
			'ornament'   => 1,
			'columns'    => array(
				array(
					'image' => tpph_media( '3-for-patients-visitors-visitors-day-patient' ),
					'title' => 'DAY PATIENT VISITORS',
					'text'  => "## Children aged 12 years and under\nAfter checking in at reception, one parent or carer may accompany children aged 12 and under, to stay with them before and after their procedure.\n## Children aged 13 to 17 years (inclusive)\nParents or carers are generally not permitted beyond reception. If there is a medical reason that requires a parent or carer to stay with the patient, this must be discussed with the treating Surgeon or Anaesthetist well before the procedure so that it can be communicated to the hospital in advance.\nPlease note that parents / carers are required to leave the hospital's clinical area while their child is in surgery. Parents / carers are welcome to wait in our reception area or enjoy a coffee / meal at one of the nearby cafes, all within easy walking distance.\n## Adults 18 years and over\nParents or carers are generally not permitted beyond reception. If there is a medical reason that requires a parent or carer to stay with the patient, this must be discussed with the treating Surgeon or Anaesthetist well before the procedure so that it can be communicated to the hospital in advance.",
				),
				array(
					'image' => tpph_media( '3-for-patients-visitors-visitors-inpatient' ),
					'title' => 'INPATIENT / OVERNIGHT PATIENT VISITORS',
					'text'  => 'Visiting hours for inpatients / patients staying overnight is between 8:00am and 8:00pm. To help maintain a restful environment, we kindly ask that no more than two visitors attend at any one time.',
				),
				array(
					'image' => tpph_media( '3-for-patients-visitors-visitors-map-tpph' ),
					'title' => 'Smoke-Free Facility',
					'text'  => "The Park Private Hospital is proud to be a smoke-free facility, which includes the hospital, surrounding car parks and gardens.\nOur smoke-free policy applies to all people who enter the grounds, including patients.\n# Location and Parking\nThe Park Private Hospital is nestled within the Mount Lawley Heritage Precinct, conveniently located at the corner of Alvan Street and Park Road.\nDue to the hospital's central location, parking is limited. Spaces are available along the paved hospital verge, and additional 2-out parking can be found along Park Road.",
				),
			),
		),
		array(
			'layout' => 'media',
			'image'  => tpph_media( '3-for-patients-visitors-visitors-visitors' ),
			'focus'  => 72,
		),
	),
);

/* --------------------------------------------- Preparing for your Admission */

$pages['for-patients-visitors/preparing-for-your-admission'] = array(
	'hero_title' => 'For Patients & Visitors',
	'hero_image' => tpph_media( '3-for-patients-visitors-header' ),
	'sections'   => array(
		array(
			'layout'         => 'text',
			'background'     => 'white',
			'heading'        => 'Preparing for your admission',
			'media_position' => 'right',
			'images'         => array( tpph_media( '3-for-patients-visitors-preparing-for-your-admission-image-11' ) ),
			'text'           => "In preparing for your stay at The Park Private Hospital, there are a few important items to organise and bring with you. Please review the information carefully to help ensure your procedure runs smoothly and your stay is as comfortable as possible.\nPrior to your admission, please ensure you:\n- Complete and return all pre-admission paperwork at least five days before your admission date. Complete your online admission here.\n- Follow any instructions provided to you by your referring doctor regarding your surgery.\n- Contact your Private Health Fund (if applicable) to confirm your level of cover. Your fund will also advise you of any excess or co-payments required under your policy. Also see Fees, Charges and Insurance.\n- Complete a Pre-Admission Telephone Call (see below).\n- Arrange a responsible adult to collect and drive you home from the hospital.\nIf you are being treated under a general anaesthetic or sedation, you will need to arrange a responsible adult to stay with you for the first 24 hours following discharge.",
		),
		array(
			'layout'         => 'text',
			'span'           => 'left',
			'background'     => 'cream',
			'ornament'       => 1,
			'heading'        => 'Pre-admission Telephone Call',
			'media_position' => 'right',
			'images'         => array( tpph_media( '3-for-patients-visitors-preparing-for-your-admission-image-81' ) ),
			'text'           => "Two days before your procedure you will receive a pre-admission telephone call. This call is essential and will include:\n- A brief health assessment\n- Your admission and fasting times\n- What to bring on the day of admission\n- An opportunity to ask any questions\n- Notify us of any specific needs you may have\nIf you miss your Pre-Admission Telephone Call, please return the call as soon as possible. Even if you receive a text message with your admission time, you are still required to call us back, as this step is essential in completing your admission process.\nIf you have not heard from our team by 5:00pm two days before your admission, please contact us.\nIf you need your admission time earlier, you may call us up to two weeks before your surgery. We can provide a tentative time; however, the final time will only be confirmed during your Pre-Admission Telephone Call.",
		),
		array(
			'layout'     => 'text',
			'span'       => 'right',
			'background' => 'cream',
			'heading'    => 'How long will I be in hospital',
			'text'       => "If you are attending for a day procedure, you should expect to be at The Park Private Hospital for 4 to 5 hours, unless advised otherwise by clinical staff. The actual time you will spend at the hospital may vary due to the nature of your procedure and other activities occurring during the day.\nThe discharge time for patients staying overnight is 8:00am, unless otherwise agreed with Hospital staff prior to admission.",
			'images'     => array(),
		),
		array(
			'layout'     => 'text',
			'span'       => 'right',
			'background' => 'cream',
			'heading'    => 'Fasting Instructions',
			'text'       => "If your procedure is in the MORNING (AM) Session:\n- Food and Milk: Do NOT consume any food or milk from 12:00 midnight the night before the morning surgery.\n- Clear Fluids: You may consume WATER only up until 2 hours before your scheduled admission time.\nIf your procedure is in the AFTERNOON (PM) Session:\n- Food and Milk: Do NOT consume food or milk from 6:00am on the day of your surgery.\n- Clear Fluids: You may consume WATER only up until 2 hours before your scheduled admission time.\n## Please Note\n- Fasting from food includes NO lollies or chewing gum.\n- If you have received different fasting advice from your anaesthetist, please follow their instructions.\n- If you are currently taking medications such as Ozempic, Wegovy, Mounjaro, Saxenda, or any diabetic medication, your fasting instructions will differ from the standard guidelines above and will be given to you by your anaesthetist.",
			'images'     => array(),
		),
		array(
			'layout'     => 'text',
			'span'       => 'left',
			'background' => 'white',
			'heading'    => 'On the day of Admission',
			'images'     => array(),
			'text'           => "Please shower before leaving home, wear comfortable, loose clothing, and remove all jewellery and piercings.\nEnsure that you arrive at the scheduled admission time.\n## What To Bring\n- All current medications, in their original packaging\n- Past medical history from your GP\n- X-Rays, scans or test results associated with your surgery\n- Any Advanced Health Care Directives\n- Medicare Card, Pension Card, Private Health Insurance Card\n- Payment for hospital fees (excess or co-payment), or total fees for uninsured / self-funded patients\n- Reading material (including glasses if required)\n- Hearing aids and case\n- Any walking aids\n- Personal toiletries (if staying overnight)\n## Do Not Bring\n- Valuables or large amounts of cash (except if required for payment on admission)\n- Jewellery\n- Hot water bottles or electric blankets\n- Sleepwear, we will provide you with a gown\nPlease be aware that The Park Private Hospital is strictly a smoke-free facility, which includes the hospital, surrounding car parks and gardens. This policy applies to all individuals who enter the grounds including patients.",
		),
		array(
			'layout'     => 'text',
			'span'       => 'right',
			'background' => 'white',
			'heading'    => 'Preparing to go Home',
			'images'     => array( tpph_media( '3-for-patients-visitors-preparing-for-your-admission-image-32' ) ),
			'text'       => "Prior to your discharge, you will receive instructions to follow when you return home. These outline the routine care required after your procedure. Please ensure you ask about anything you are unsure of before leaving the hospital.\nWhile the major effects of anaesthetic wear off quickly, minor effects such as changes in memory, balance, and muscle function may continue for several hours.\nThese effects vary from person to person and cannot be predicted, so please take note of the following:\n- A family member or carer must collect and drive you home from hospital after your procedure.\n- A responsible adult must stay with you for at least 24 hours after your procedure.\n- Do not drive a care or operate heavy machinery for 24 hours.\n- Do not consume any alcohol for at least 24 hours after your procedure.\nOnce home, if you have any further concerns, please contact your doctor or your general practitioner.",
		),
	),
);

/* ------------------------------------------------------- Post Operative Care */

$pages['for-patients-visitors/post-operative-care'] = array(
	'hero_title' => 'For Patients & Visitors',
	'hero_image' => tpph_media( '3-for-patients-visitors-header' ),
	'sections'   => array(
		array(
			'layout'         => 'text',
			'background'     => 'white',
			'heading'        => 'Post Operative Care',
			'media_position' => 'right',
			'images'         => array(
				tpph_media( '3-for-patients-visitors-post-operative-care-image-16' ),
				tpph_media( '3-for-patients-visitors-post-operative-care-image-42' ),
				tpph_media( '3-for-patients-visitors-post-operative-care-image-87' ),
			),
			'text'           => "Please follow any specific instructions provided to you by your surgeon or anaesthetist after discharge. Strict adherence to post-operative instructions will support your recovery.\nIf you are concerned about your wellbeing, are unable to manage your pain, feel nauseous, or experience an abnormal reaction to your medication, please contact the hospital, your surgeon or anaesthetist, or your nearest emergency department.\nWhile the major effects of anaesthetic wear off quickly, minor effects such as changes in memory, balance, and muscle function may continue for several hours.\nThese effects vary from person to person and cannot be predicted, so please take note of the following:\n- A family member or carer must collect and drive you home from hospital after your procedure.\n- A responsible adult must stay with you for at least 24 hours after your procedure.\n- Do not drive a care or operate heavy machinery for 24 hours.\n- Do not consume any alcohol for at least 24 hours after your procedure.\nWe also recommend:\n- Drinking plenty of fluids and diet plan as instructed by your doctor.\n- Follow all medication management plans.\n- Minimise physical activity and exercise for the first 48 hours or as directed by your doctor.",
		),
	),
);

/* ------------------------------------------ Patient Rights & Responsibilities */

$pages['for-patients-visitors/patient-rights-responsibilities'] = array(
	'hero_title' => 'For Patients & Visitors',
	'hero_image' => tpph_media( '3-for-patients-visitors-header' ),
	'sections'   => array(
		array(
			'layout'         => 'text',
			'background'     => 'white',
			'heading'        => 'Rights and Responsibilities',
			'media_position' => 'right',
			'media_overhang' => 1,
			'images'         => array(
				tpph_media( '3-for-patients-visitors-rights-and-responsibilities-image-9' ),
				tpph_media( '3-for-patients-visitors-rights-and-responsibilities-image-5' ),
				tpph_media( '3-for-patients-visitors-rights-and-responsibilities-image-7' ),
			),
			'text'           => 'At The Park Private Hospital, we are committed to providing safe, respectful and high quality care. We believe that healthcare works best when patients, families, carers and healthcare providers work together. This page outlines the rights you can expect when receiving care with us, as well as the responsibilities that support a safe and positive experience for everyone.',
		),
		array(
			'layout'     => 'text',
			'background' => 'cream',
			'ornament'   => 1,
			'heading'    => 'Your Rights',
			'images'     => array(),
			'text'       => "As a patient at The Park Private Hospital, you have the right to:\n## Access\nHealthcare services and treatment that meet their needs.\n## Safety\nReceive safe and high quality health care that meets national standards.\nBe cared for in an environment that is safe and makes them feel safe.\n## Respect\nBe treated as an individual, and with dignity and respect.\nHave your culture, identity, beliefs and choices recognised and respected.\n## Partnership\nAsk questions and be involved in open and honest communication.\nMake decisions with your healthcare provider, to the extent that your choose and are able to.\nInclude the people you want in planning and decision making.\n## Information\nClear information about your condition, and the possible benefits and risks of different tests and treatments, so you can give informed consent.\nReceive information about services, waiting times and costs.\nBe given assistance, when needed, to help you understand and use health information.\n## Access your health information\nBe told if something has gone wrong during your health care, how it happened, how it may affect you and what is being done to make care safe.\n## Privacy\nHave your personal privacy respected.\nHave information about you and your health kept secure and confidential.\n## Give feedback\nProvide feedback or make a complaint without it affecting the way you are treated.\nHave your concerns addressed in a transparent and timely way.\nShare your experience and participate to improve the quality of care and health services.\nFor more information on patient rights, please visit the Australian Commission on Safety and Quality in Health Care here.",
		),
		array(
			'layout'         => 'text',
			'background'     => 'white',
			'heading'        => 'Your Responsibilities',
			'media_position' => 'left',
			'media_width'    => 'third',
			'images'         => array( tpph_media( '3-for-patients-visitors-rights-and-responsibilities-image-11' ) ),
			'text'           => "As a patient of The Park Private Hospital, you also have responsibilities that help us provide safe, respectful and effective care. These include:\n## Provide Accurate Information\nProvide, to the best of your knowledge, complete and accurate information about your medical history, medications, allergies and any other factors related to your health.\nInform your doctor or caregivers of any changes in your condition or concerns about your care or treatment.\n## Ask Questions and Communicate Needs\nAsk questions if you do not understand any aspect of your care, treatment or instructions.\nInform caregivers of any special requirements, including cultural, religious or personal needs.\n## Follow Care Instructions\nFollow the treatment plan and post procedure instructions recommended by your healthcare team, or discuss any concerns with your doctor if you are unable or unwilling to do so.\n## Show Respect\nTreat caregivers, staff, other patients and visitors with courtesy and respect.\nSupport a safe and pleasant environment for everyone.\nUnderstand that caregivers may withdraw care if a person behaves aggressively, violently or abusively.\n## Use Hospital Resources Responsibly\nUse hospital property, equipment and facilities appropriately and with care.\n## Infection Prevention\nFollow any infection prevention measures in place, such as hand hygiene or mask requirements, when relevant.\n## Financial Responsibilities\nBe aware of your private health fund coverage and any associated restrictions.\nProvide accurate information regarding your ability to pay for services and ensure financial obligations are met promptly.",
		),
		array(
			'layout' => 'media',
			'image'  => tpph_media( '3-for-patients-visitors-rights-and-responsibilities-image-15' ),
			'focus'  => 77,
		),
	),
);

/* ----------------------------------------------------------- For Doctors */

$pages['for-doctors'] = array(
	'hero_title' => 'For Doctors',
	'hero_image' => tpph_media( '4-for-doctors-header' ),
	'sections'   => array(
		array(
			'layout'     => 'text',
			'background' => 'cream',
			'ornament'   => 1,
			'heading'    => 'Credentialing',
			'images'     => array(),
			'text'       => TPPH_LOREM_1 . "\n## Evaluation of credentialing applications\n" . TPPH_LOREM_1 . ' ' . TPPH_LOREM_1 . "\n" . TPPH_LOREM_1,
		),
	),
);

/* ------------------------------------------------------ Safety and Quality */

$pages['safety-and-quality'] = array(
	'hero_title' => 'Safety and Quality',
	'hero_image' => tpph_media( '3-for-patients-visitors-header' ),
	'sections'   => array(
		array(
			'layout'     => 'text',
			'background' => 'cream',
			'ornament'   => 1,
			'heading'    => 'Accreditation and Licensing',
			'images'     => array(),
			'text'       => TPPH_LOREM_1 . "\n## Lorem ipsum dolor\n" . TPPH_LOREM_1 . ' ' . TPPH_LOREM_1 . "\n" . TPPH_LOREM_1,
		),
	),
);

/* ------------------------------------------------------------------- Apply */

foreach ( $pages as $path => $data ) {
	$page = get_page_by_path( $path );

	if ( ! $page ) {
		echo "missing page: {$path}\n";
		continue;
	}

	update_post_meta( $page->ID, '_wp_page_template', 'page-templates/sections.php' );

	update_field( 'hero_title', $data['hero_title'], $page->ID );
	update_field( 'hero_image', $data['hero_image'], $page->ID );
	update_field( 'sections', $data['sections'], $page->ID );

	echo 'seeded ' . $path . ' (' . count( $data['sections'] ) . " sections)\n";
}

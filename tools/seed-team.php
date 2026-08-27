<?php
/**
 * Populate About — Our Team from artboards `6. Careers - 1` and `- 5`.
 *
 * Four executives as a page repeater, eight doctors as posts.
 *
 * Four doctors have no qualifications line in the design — the artboard shows
 * magenta placeholder text where it should be — and two executives have no
 * photograph. Both gaps are left empty here and go on the client list. Guessing
 * at a surgeon's post-nominals is not something to do quietly.
 */

function tpph_media( $slug ) {
	$map = get_option( 'tpph_media_map', array() );
	return isset( $map[ $slug ] ) ? (int) $map[ $slug ] : 0;
}

/** The design leaves the executive biographies as lorem ipsum. */
const TPPH_LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

$page = get_page_by_path( 'about/our-team' );

if ( ! $page ) {
	echo "our team page missing\n";
	return;
}

update_post_meta( $page->ID, '_wp_page_template', 'page-templates/our-team.php' );

/* -------------------------------------------------------------- Page fields */

update_field( 'hero_image', tpph_media( '6-about-us-header' ), $page->ID );
// The artboard puts the parent section's name in the banner on every child
// page — "For Patients & Visitors" on all three of its children, and "About"
// here — not the page's own title.
update_field( 'hero_title', 'About', $page->ID );

update_field( 'exec_heading', 'Executive Team', $page->ID );
update_field(
	'exec_intro',
	"The Executive Team provides leadership and governance across the hospital, ensuring our services are delivered safely, effectively, and with a strong focus on patient care.\n\nTogether, the team oversees clinical quality, operational management, workforce leadership, and the ongoing development of hospital services.",
	$page->ID
);

update_field(
	'executives',
	array(
		array(
			'photo'          => 0, // Not supplied — magenta placeholder in the artboard.
			'name'           => 'A/Prof Dieter Gebauer',
			'role'           => 'Medical Director',
			'qualifications' => 'BDSc, MBBS(Hons), FRACDS, MclinRes',
			'biography'      => TPPH_LOREM,
		),
		array(
			'photo'          => tpph_media( '6-about-us-exec-team-brendon-garton' ),
			'name'           => 'Brendon Garton',
			'role'           => 'Chief Executive Officer',
			'qualifications' => 'BSc, Dip PM, MHAPL, MBA',
			'biography'      => TPPH_LOREM,
		),
		array(
			'photo'          => tpph_media( '6-about-us-exec-team-helen-robinson' ),
			'name'           => 'Helen Robinson',
			'role'           => 'Director of Nursing',
			'qualifications' => 'BSc (Nursing), Dip Bus, Adv Dip Mgt, GAICD',
			'biography'      => TPPH_LOREM,
		),
		array(
			'photo'          => 0, // Not supplied.
			'name'           => 'Jan Morskate',
			'role'           => 'Director of Nursing',
			'qualifications' => 'BSc(Nursing)',
			'biography'      => TPPH_LOREM,
		),
	),
	$page->ID
);

update_field( 'doctors_heading', 'Our Doctors', $page->ID );
update_field( 'doctors_intro', 'Our Doctors provide specialised care grounded in excellence, guiding patients through every stage of their health journey.', $page->ID );
update_field( 'show_filters', 1, $page->ID );

/* ------------------------------------------------------------------ Doctors */

$doctors = array(
	array(
		'name'      => 'Dr Eric Tai',
		'role'      => 'Specialist Anaesthetist',
		'quals'     => '', // Missing in the design.
		'specialty' => 'Anaesthetists',
		'photo'     => '6-about-us-doctors-dr-eric-tai',
		'bio'       => "Dr Eric Tai is a Specialist Anaesthetist who has been practising at The Park Private Hospital since its establishment over 12 years ago. Working regularly with multiple Maxillofacial Surgeons he has extensive experience providing anaesthesia for both major and minor maxillofacial surgery.\n\nDr Tai completed his medical and specialist anaesthesia training in Western Australia, working across Sir Charles Gairdner Hospital, Royal Perth Hospital, Perth Children's Hospital (formerly Princess Margaret Hospital), and Fremantle Hospital.\n\nHe also holds a public appointment as a Consultant Anaesthetist at Sir Charles Gairdner Hospital, where he continues to practise in a tertiary hospital environment managing a broad and complex case mix.",
	),
	array(
		'name'      => 'Dr Peter Ricciardo',
		'role'      => 'Oral and Maxillofacial Surgeon',
		'quals'     => 'BSc, BDSc, MBBS, FRACDS(OMS), MRCPS(Glasg)',
		'specialty' => 'Oral and Maxillofacial Surgeons',
		'photo'     => '6-about-us-doctors-dr-peter-ricciardo',
		'bio'       => "Peter completed training in Oral and Maxillofacial Surgery in Western Australia, before proceeding to three years of advanced sub-specialty fellowship training in: Paediatric Oral and Maxillofacial Surgery (Royal Children's Hospital, Melbourne), Orthognathic and Trauma Surgery (Royal Melbourne Hospital, Melbourne), Temporomandibular Joint Surgery (St Vincent's Hospital, Melbourne), and Head and Neck Surgery (Queen Elizabeth University Hospital, Glasgow).\n\nPeter is Head of Department of Oral and Maxillofacial Surgery at Royal Perth Hospital, and consultant surgeon at Perth Children's Hospital.",
	),
	array(
		'name'      => 'Dr Nathan Vujcich',
		'role'      => 'Oral and Maxillofacial Surgeon',
		'quals'     => '', // Missing in the design.
		'specialty' => 'Oral and Maxillofacial Surgeons',
		'photo'     => '6-about-us-doctors-dr-nathan-vujcich',
		'bio'       => "Dr Vujcich is an oral and maxillofacial surgeon who received his medical and dental degrees (both with Honours) from the University of Western Australia, before completing his advanced surgical training in Perth and Melbourne. He is currently a consultant at Royal Perth Hospital and Perth Children's Hospital, in addition to his private practice.\n\nDr Vujcich is heavily involved in training of the states Oral & Maxillofacial Surgery registrars, and examines for the national college. In addition to this, he is currently (2025-2027) the National president of ANZAOMS, the nations association for Oral & Maxillofacial Surgeons.\n\nHe has worked at The Park Private Hospital since its renovation and reopening over 10 years ago.",
	),
	array(
		'name'      => 'DR ROB CHOA',
		'role'      => 'Specialist Plastic Surgeon',
		'quals'     => '', // Missing in the design.
		'specialty' => 'Plastic Surgeons',
		'photo'     => '6-about-us-doctors-dr-rob-choa-jpg',
		'bio'       => "Dr Rob Choa (MED0002049063) is Specialist Plastic Surgeon both in Australia (FRACS) and the UK (FRCSEd Plast), who has been working in Perth since 2016. He has gained significant exposure to all aspects of aesthetic plastic surgery and is a trusted name in breast, body and facial procedures.\n\nHe is accredited to undertake plastic surgery operations, both medical and cosmetic, at The Park Private Hospital.\n\nDr Choa attended Liverpool Medical School in the UK, graduating with honours in 2005. During his time at medical school, he undertook an additional degree in Anatomy, obtaining first class honours. Throughout his training he has worked at a number of prestigious hospitals, including the Queen Elizabeth Hospital Birmingham, Chelsea and Westminster Hospital in London and the Nuffield Orthopaedic Centre in Oxford.",
	),
	array(
		'name'      => 'Dr Joseph Luo',
		'role'      => 'Specialist Plastic Surgeon',
		'quals'     => '', // Missing in the design.
		'specialty' => 'Plastic Surgeons',
		'photo'     => '6-about-us-doctors-dr-joseph-luo',
		'bio'       => "Dr Joseph Luo (AHPRA MED0001673981) is a specialist plastic and reconstructive surgeon, having completed his medical degree at the University of Western Australia and advanced surgical training at leading plastic surgery centres in Perth and Sydney. He is a Fellow of the Royal Australasian College of Surgeons (RACS), recognised for his expertise and commitment to excellence in the field.\n\nDriven by a strong sense of global service, Dr Luo spent a year volunteering as a plastic surgeon in rural Africa, providing care for patients with complex craniofacial, body and limb conditions. He was awarded the College of Surgeons of East, Central and Southern Africa (COSECSA) by examination and received the prestigious Jimmy James Prize as the top candidate in Plastic Surgery.\n\nDr Luo went on to complete a one-year fellowship in craniomaxillofacial surgery at the internationally acclaimed Chang Gung Memorial Hospital in Taipei. During this time he was awarded the Chang Gung scholarship, then subsequently awarded a PFET (Post Fellowship Education and Training) subspecialty qualification in Craniomaxillofacial surgery. He has also pursued further training in Taiwan, Korea, Malaysia and Singapore, learning advanced techniques from masters in aesthetic surgery.",
	),
	array(
		'name'      => 'Mr Paul Armanasco',
		'role'      => 'Specialist Surgical Podiatrist',
		'quals'     => 'BSc MSc FACPS FFPM RCPS (Glas)',
		'specialty' => 'Podiatric Surgeon',
		'photo'     => '6-about-us-doctors-mr-paul-armanasco',
		'bio'       => "Mr Paul Armanasco BSc MSc FACPS FFPM RCPS (Glas) is a Specialist Surgical Podiatrist based in the Southwest of Western Australia. He completed his fellowship exams and attained specialist registration in 2012. He finished his undergraduate podiatry degree in 1998, his masters in 2006 and trained with the Australasian College of Podiatric Surgeons between 2006 and 2012.\n\nDuring this time he undertook rotation at the Great Western Hospital National Health Service (NHS) trust Orthopaedic department in the UK, completed a number of international (USA) and interstate rotations, and attended extensive foot and ankle surgical training in Australia. He has completed numerous foot and ankle surgical training courses in Australia, USA, Malaysia and England.\n\nHe has also published a number of peer-reviewed papers on foot and ankle surgery, routinely audits his surgery outcomes and regularly attends courses and conferences on foot and ankle surgery. He is actively involved in teaching Podiatric Medicine, Medical students as well as Podiatric Surgery trainees.",
	),
	array(
		'name'      => 'Julie Taranto',
		'role'      => 'Podiatric Surgeon',
		'quals'     => 'B.Sc (Pod) Hons (Curtin) P.Grad.Dip (Pod) (Curtin) M.Med.Sc.(UWA), FACPS',
		'specialty' => 'Podiatric Surgeon',
		'photo'     => '6-about-us-doctors-julie-taranto',
		'bio'       => "Julie is an accomplished and dedicated Podiatric Surgeon.\n\nHer research papers have been published in international scientific journals including The Foot, Journal of Foot and Ankle Surgery and the Journal of the American Podiatric Medical Association.\n\nJulie enjoys running in her spare time and has undertaken 7 marathons so far. She enjoys maintaining a healthy lifestyle and is actively involved in the community, having participated in the MACA Ride to Conquer Cancer. Julie enjoys keeping fit and undertaking team events, especially when it's a shared team event with Michael.\n\nA mother of three teenage children herself, she understands the pressures of family life and gives down-to-earth, practical advice to her patients.",
	),
	array(
		'name'      => 'Michael J Taranto',
		'role'      => 'Podiatric Surgeon',
		'quals'     => 'B.Sc (Pod) Hons (Curtin) P.Grad.Dip (Pod) (Curtin) M.Med.Sc.(UWA), FACPS',
		'specialty' => 'Podiatric Surgeon',
		'photo'     => '6-about-us-doctors-michael-j-taranto',
		'bio'       => "Michael is an accomplished and dedicated Podiatric Surgeon.\n\nWhilst studying at Curtin University, Michael achieved membership to the Vice Chancellors List, recognising him among the top 1% of Curtin University students.\n\nHe has also had his research papers published in international scientific journals including The Foot, Journal of Foot and Ankle Surgery and the Journal of the American Podiatric Medical Association.\n\nMichael loves swimming. He regularly swims in the annual Busselton Jetty Swim and has undertaken the Rottnest Channel solo crossing three times. A swim event highlight has been undertaking the Lake Argyle Swim in the spectacular north-west of the State.\n\nKeeping fit helps him tag team with his equally fit wife, Julie, to provide the best patient care and availability in the clinic.",
	),
);

foreach ( $doctors as $order => $doctor ) {
	$existing = get_posts(
		array(
			'post_type'      => 'doctor',
			'title'          => $doctor['name'],
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	$id = $existing
		? $existing[0]
		: wp_insert_post(
			array(
				'post_type'   => 'doctor',
				'post_title'  => $doctor['name'],
				'post_status' => 'publish',
			)
		);

	wp_update_post(
		array(
			'ID'         => $id,
			'menu_order' => $order + 1,
		)
	);

	update_field( 'role', $doctor['role'], $id );
	update_field( 'qualifications', $doctor['quals'], $id );
	update_field( 'biography', $doctor['bio'], $id );

	wp_set_object_terms( $id, $doctor['specialty'], 'specialty', false );

	$photo = tpph_media( $doctor['photo'] );

	if ( $photo ) {
		set_post_thumbnail( $id, $photo );
	}

	echo 'doctor ' . ( $existing ? 'updated' : 'created' ) . ': ' . $doctor['name'] . "\n";
}

echo "\nour team populated (#{$page->ID})\n";

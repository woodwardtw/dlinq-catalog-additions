const url = 'https://experiments.middcreate.net/faqs/wp-json/wp/v2/course-listing?id=60'


async function makePage(url) {
  const response = await fetch(url);
  const data = await response.json();
  const destination = document.querySelector('#holder');
  const acfData = data[0].acf;
  const title = data[0].title.rendered;

  const exciteHTML = (acfData.discount_banner != '') ? `<p class="excite">${acfData.discount_banner}</p>` : '';
  const courseOverview = acfData.course_overview;

  const courseFeatureHMTL = courseFeatureList(acfData.course_features);
  
  const forYou = acfData.this_course_is_for_you;
  const forYouHTML = makeParagraphs(forYou);

  const videoURL = acfData.panopto_video_url;

  const outcomeHTML = courseOutcomes(acfData.what_you_will_learn);

  const bio = acfData.instructor_bio;
  const bioImgHTML = fetchBioImg(data);

  destination.innerHTML = `
  			<h1>${title}</h1>
  			${exciteHTML}
  			<div id="c-holder">
  				<div id="c-left">
  				<h2>Course Overview</h2>
  					<p>${courseOverview}</p>
  					<ul>
  						${courseFeatureHMTL}
  					</ul>
  				<h2>This course is for you if . . . </h2>
  						${forYouHTML}
  				</div><!--end left-->
  				<div id="c-right">
  					<div class="video-responsive">
  						<iframe style="border: 1px solid #464646;" src="${videoURL}&autoplay=false&offerviewer=false&showtitle=false&showbrand=true&captions=false&interactivity=none" width="720" length="405"></iframe>
  					</div>
  						<div class="outcomes">
  							<h2>What you will learn</h2>
  							${outcomeHTML}
  						</div>
  					</div>
  				</div><!--end right-->
  				<div id="bio-block" class="full bio">
  					${bioImgHTML}
  					<h2>About the instructor</h2>
  					<p>${bio}</p>
  				</div><!--end bio-->
  			</div>


  `;
}

makePage(url);

function courseFeatureList(items){
	let features = '';
	items.forEach((item) => {
		const featureName = item.feature_name
		const featureValue = item.feature_value
		features += `<li><strong>${featureName}:</strong> ${featureValue}</li>`
	});
	return features;
}

function makeParagraphs(data){
	let paragraphHTML = '';
	const paragraphs = data.split('\r\n\r\n');
	paragraphs.forEach((paragraph) => {
		paragraphHTML += `<p>${paragraph}</p>`
	})
	return paragraphHTML;
}

function courseOutcomes(items){
	let outcomes = '';
	items.forEach((item) => {
		outcomes += `<li>${item.learning_outcome}</li>`
	});
	return outcomes;
}

function fetchBioImg(data){
	const imageURL = data;
	
  		console.log(data[0])
  		return `<img class="bio-pic" src="${imageURL}>`;

}
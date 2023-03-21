/**
 * @fileoverview JavaScript utility to query the AMC ActDB API and render a list of events
 * in the DOM. Provided as part of the Wordpress plugin amc-activities-shortcode
 *
 * @author marty@graybirch.solutions (Martin Jensen)
 *
 * @link              https://github.com/graybirchsolutions/amc-activities-shortcode
 * @since             1.1.0
 *
 * @package           amc-activities-shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       AMC Activities Shortcode
 * Plugin URI:        https://github.com/graybirchsolutions/amc-activities-shortcode
 * Description:       Display events from the AMC Activities Database via shortcode. Data is retrieved from the Activities Database XML API via a simple HTTP query. Activities are re-formatted as HTML blocks and displayed in the page or post as events. <strong>Usage: [amc_activities chapter=id committee=id activity=id display=[short|long] limit=n]</strong>. Chapter is the only required parameter, all other parameters are optional. Display defaults to short. Limit defaults to 10.
 * Version:           2.0.3
 * Author:            gray birch solutions
 * Author Email:      marty@graybirch.solutions
 * Author URI:        https://graybirch.solutions/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

const AMCACTDB_API_ACTIVITIES_BASE = '/wp-json/AMCActivities/1.0/activities'

const myscript = document.getElementById('amc-activities-render-events-js')
const myscripturl = myscript.src

const AMC_ACTIVITIES_ASSETDIR_URL = myscripturl.slice(0, myscripturl.indexOf('/js/'))

const renderEvent = function (activity) {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

  const dt = activity.trip_datetime.split(' ')
  const darr = dt[0].split('-')
  const tarr = dt[1].split(':')
  const sDate = new Date(darr[0], darr[1] - 1, darr[2], tarr[0], tarr[1])

  let min = sDate.getMinutes()
  if (min < 10) {
    min = '0' + min
  }
  let hr = sDate.getHours()
  let ampm = 'am'
  if (hr > 12) {
    hr -= 12
    ampm = 'pm'
  }

  // Format the date block

  const ys = document.createElement('span')
  ys.className = 'year'
  ys.textContent = sDate.getFullYear()

  const ms = document.createElement('span')
  ms.className = 'month'
  ms.textContent = months[sDate.getMonth()]

  const ds = document.createElement('span')
  ds.className = 'date'
  ds.textContent = sDate.getDate()

  const sdb = document.createElement('span')
  sdb.className = 'amc-start-date'
  sdb.appendChild(ds)
  sdb.appendChild(ms)
  sdb.appendChild(ys)

  const db = document.createElement('div')
  db.className = 'amc-date-block'
  db.appendChild(sdb)

  // The Event Description Block
  // Format the event title
  const ta = document.createElement('a')
  ta.textContent = activity.trip_title
  ta.href = activity.trip_url
  ta.target = '_blank'

  const title = document.createElement('span')
  title.className = 'amc-event-title'
  title.appendChild(ta)

  // Format the event date string
  const dd = document.createElement('span')
  dd.className = 'amc-event-date'
  dd.textContent = `${days[sDate.getDay()]} ${months[sDate.getMonth()]} ${sDate.getDate()}, ${sDate.getFullYear()}`
  // eslint-disable-next-line eqeqeq
  if (!sDate.getHours() == '00') {
    dd.textContent += ` at ${hr}:${min}${ampm}`
  }

  // Format the event status
  const ks = document.createElement('span')
  ks.className = 'key'
  ks.textContent = 'Status'

  const escolon = document.createTextNode(':')

  const es = document.createElement('span')
  switch (activity.trip_status) {
    case 'Open':
      es.className = 'amc-status-open'
      break
    case 'Canceled':
      es.className = 'amc-status-canceled'
      break
    case 'Wait Listed':
      es.className = 'amc-status-waitlist'
      break
  }
  es.textContent = activity.trip_status

  const st = document.createElement('span')
  st.className = 'amc-event-status'
  st.appendChild(ks)
  st.appendChild(escolon)
  st.appendChild(es)

  // Format the event committee
  const comkey = document.createElement('span')
  comkey.className = 'key'
  comkey.textContent = 'Committee'

  const comtxt = document.createTextNode(`: ${activity.trip_committee}`)

  const comspan = document.createElement('span')
  comspan.className = 'amc-event-committee'
  comspan.appendChild(comkey)
  comspan.appendChild(comtxt)

  // Event Description Lead Block - Contains date string, status and committee
  const edlb = document.createElement('div')
  edlb.className = 'amc-event-desc-lead'
  edlb.appendChild(dd)
  edlb.appendChild(st)
  edlb.appendChild(comspan)

  // Event Descripton Info - Contains event type, event leader and event description
  const edi = document.createElement('div')
  edi.className = 'amc-event-desc-info'

  // Event Types - Loop through the array

  for (let i = 0; i < activity.trip_activities.length; i++) {
    // Create the activity span and append it to Event Description Info
    const etk = document.createElement('span')
    etk.className = 'key'
    etk.textContent = 'Activity'

    const ett = document.createTextNode(`: ${activity.trip_activities[i]}`)

    const et = document.createElement('span')
    et.className = 'amc-event-type'
    et.appendChild(etk)
    et.appendChild(ett)

    edi.appendChild(et)
  }

  // Trip Leader

  const eld = document.createElement('span')
  eld.className = 'amc-event-leader'

  const tlk = document.createElement('span')
  tlk.className = 'key'
  tlk.textContent = 'Leader'

  eld.appendChild(tlk)

  if (!activity.trip_leader_email === '') {
    const tlto = document.createTextNode(`: ${activity.trip_leader} <`)
    const eml = document.createElement('a')
    eml.href = `mailto:${activity.trip_leader_email}`
    eml.textContent = activity.trip_leader_email
    const tltc = document.createTextNode('>')
    eld.appendChild(tlto)
    eld.appendChild(eml)
    eld.appendChild(tltc)
  } else {
    const tlt = document.createTextNode(`: ${activity.trip_leader}`)
    eld.appendChild(tlt)
  }

  edi.appendChild(eld)

  // Event Description - Contains event description lead and event description info
  const desc = document.createElement('div')
  desc.className = 'amc-event-desc'
  desc.appendChild(edlb)
  desc.appendChild(edi)

  // Event Location - Optional in Event Desription

  if (!activity.trip_location === '') {
    const evl = document.createElement('div')
    evl.className = 'amc-event-location'

    const elk = document.createElement('span')
    elk.className = 'key'
    elk.textContent = 'Location'
    evl.appendChild(elk)

    const elt = document.createTextNode(`: ${activity.trip_location}`)
    evl.appendChild(elt)

    desc.appendChild(evl)
  }

  // Event Description Block - Contains Title, Event Description and Event Location
  const edb = document.createElement('div')
  edb.className = 'amc-event-desc-block'
  edb.appendChild(title)
  edb.appendChild(desc)

  const checkbox = document.createElement('input')
  checkbox.type = 'checkbox'
  checkbox.addEventListener('change', function () {
    // Find the event detail block
    const evwrap = this.closest('.amc-event-wrap')
    const evdetails = evwrap.querySelector('.amc-event-details')
    if (this.checked) {
      evdetails.style.display = 'flex'
      evdetails.style.visibility = 'visible'
      evdetails.style.opacity = '1'
    } else {
      evdetails.style.display = 'none'
      evdetails.style.visibility = 'hidden'
      evdetails.style.opacity = '0'
    }
  })

  const slider = document.createElement('span')
  slider.className = 'amc-slider'
  slider.className += ' round'

  const tooltip = document.createElement('span')
  tooltip.className = 'tooltip'
  tooltip.textContent = 'hide/show event description'

  const toggle = document.createElement('label')
  toggle.className = 'amc-switch'
  toggle.appendChild(checkbox)
  toggle.appendChild(slider)
  toggle.appendChild(tooltip)

  // Event Wrap - Contains the Date Block and Event Description Block
  const wrap = document.createElement('div')
  wrap.className = 'amc-event-wrap'
  wrap.appendChild(db)
  wrap.appendChild(toggle)
  wrap.appendChild(edb)

  // Render additional data - The featured image and the full description.

  const details = document.createElement('div')
  details.className = 'amc-event-details'

  const img = document.createElement('img')
  if (!activity.trip_images.length === 0) {
    img.src = `https:${activity.trip_images[0]}`
  } else {
    // Fetch a random seeded image from our own assets
    img.src = `${AMC_ACTIVITIES_ASSETDIR_URL}/img/AMC_Logo_${Math.floor(Math.random() * 10) + 1}.svg`
    img.width = '200'
    img.height = '200'
  }

  const imgdiv = document.createElement('div')
  imgdiv.className = 'amc-event-image'
  imgdiv.appendChild(img)

  const fulldiv = document.createElement('div')
  fulldiv.className = 'amc-event-long-desc'

  const fulltxt = document.createElement('p')
  fulltxt.className = 'wrap-text'
  fulltxt.textContent = activity.trip_description
  fulldiv.appendChild(fulltxt)

  const evmore = document.createElement('div')
  evmore.className = 'amc-more-details'
  const morx1 = document.createTextNode('To find out more or to register for this event please see ')
  const mora1 = document.createElement('a')
  mora1.href = activity.trip_url
  mora1.textContent = 'the event page'
  mora1.target = '_blank'
  const morx2 = document.createTextNode(' on the AMC\'s Activities website at ')
  const mora2 = document.createElement('a')
  mora2.href = 'https://activities.outdoors.org'
  mora2.textContent = 'activities.outdoors.org'
  mora2.target = '_blank'
  evmore.appendChild(morx1)
  evmore.appendChild(mora1)
  evmore.appendChild(morx2)
  evmore.appendChild(mora2)

  details.appendChild(imgdiv)
  details.appendChild(fulldiv)
  details.appendChild(evmore)

  edb.appendChild(details)

  return wrap
}

const renderNoEvents = function (eventBlock) {
  const wrap = document.createElement('div')
  wrap.className = 'amc-event-wrap'

  const block = document.createElement('div')
  block.className = 'amc-event-desc-block'

  const title = document.createElement('div')
  title.className = 'amc-event-title'
  title.innerHTML = 'Sorry!'

  const desc = document.createElement('div')
  desc.className = 'amc-event-desc'
  desc.innerHTML = 'No upcoming events were found in the AMC Activities Calendar. Please check back frequently as we are often adding new trips and events to the calendar.'

  block.appendChild(title)
  block.appendChild(desc)
  wrap.appendChild(block)
  eventBlock.appendChild(wrap)
}

const renderBadQuery = function (eventBlock) {
  const wrap = document.createElement('div')
  wrap.className = 'amc-event-wrap'

  const block = document.createElement('div')
  block.className = 'amc-event-desc-block'

  const title = document.createElement('div')
  title.className = 'amc-event-title'
  title.innerHTML = 'Oops!'

  const desc = document.createElement('div')
  desc.className = 'amc-event-desc'
  desc.innerHTML = 'Something went wrong when we tried to look for events in the AMC Activities Calendar. If this message persists, please contact the site administrator and let him know!'

  block.appendChild(title)
  block.appendChild(desc)
  wrap.appendChild(block)
  eventBlock.appendChild(wrap)
}

const renderEvents = function (eventBlock, activities) {
  for (let i = 0; i < activities.length; i++) {
    const activity = activities[i]
    eventBlock.appendChild(renderEvent(activity))
  }
}

function renderAMCEvents () {
  // First, find all instances of divs with class="amc-events-container"

  const eventBlocks = document.querySelectorAll('.amc-events-container')

  if (eventBlocks.length > 0) {
    for (let i = 0; i < eventBlocks.length; i++) {
      const eventBlock = eventBlocks[i]

      const chapter = eventBlock.dataset.chapter
      const committee = eventBlock.dataset.committee
      const activity = eventBlock.dataset.activity
      const limit = eventBlock.dataset.limit

      let queryURL = `${AMCACTDB_API_ACTIVITIES_BASE}?chapter=${chapter}`

      if (committee !== '') {
        queryURL += '&committee=' + committee
      }

      if (activity !== '') {
        queryURL += '&activity=' + activity
      }

      if (limit !== '') {
        queryURL += '&limit=' + limit
      }

      renderBlock(eventBlock, queryURL)
    }
  }
}

async function renderBlock (eventBlock, queryURL) {
  const response = await fetch(queryURL)
  const loader = eventBlock.querySelector('.amc-loader')

  if (!response.ok || response.status === 204) {
    if (response.status === 204) {
      renderNoEvents(eventBlock)
    } else if (response.status === 400) {
      console.log('Bad request: Error 400 - Likely due to missing chapter in the server request to AMC Activities Database')
      console.log('             Make sure the amc-activities-shortcode has included a valid chapter number.')
      renderBadQuery(eventBlock)
    } else if (response.status === 404) {
      console.log('Error 404 - Page or API Route not found on server.')
      renderBadQuery(eventBlock)
    } else {
      throw new Error('Unknown Response: Error ' + response.status + ' - ' + response.statusText)
    }
  } else {
    const activities = await response.json()

    renderEvents(eventBlock, activities)
  }
  loader.remove()
}

if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
  renderAMCEvents()
} else {
  document.addEventListener('DOMContentLoaded', renderAMCEvents)
}

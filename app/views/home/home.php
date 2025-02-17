<style>
  /* Define the CSS for the scrollable div */
  .scrollable-div {
    max-height: 400px;
    /* Set the maximum height to make it scrollable */
    overflow: auto;
  }

  /* annouce List hove */
  .annouceList {
    border-bottom: 1px solid #d9dee3;
  }

  .annouceList:hover {
    background-color: #f5f5f9;
  }

  .fc-event-title {
    font-size: 1.2rem;
    color: #fff;
  }

  /* .shortcut-logo hover to expand */
  .shortcut-logo:hover {
    transform: scale(1.2);
  }

  .pinned {
    background-color: #FFFADF !important;
  }
</style>

<?php

function thaiDateFormat($date)
{
  $thaiMonths = array(
    "01" => "ม.ค.",
    "02" => "ก.พ.",
    "03" => "มี.ค.",
    "04" => "เม.ย.",
    "05" => "พ.ค.",
    "06" => "มิ.ย.",
    "07" => "ก.ค.",
    "08" => "ส.ค.",
    "09" => "ก.ย.",
    "10" => "ต.ค.",
    "11" => "พ.ย.",
    "12" => "ธ.ค."
  );

  $date = explode("-", $date);
  $year = $date[0] + 543;
  $month = $thaiMonths[$date[1]];
  $day = $date[2];

  return "$day $month $year";
}

?>

<div class="container-xxl flex-grow-1 container-p-y">
 
  <div class="row g-3">
    <!-- greet user by time period -->
    <div class="col-12 mb-1">
      <span class="card-text fs-3 fw-bold pt-3 d-inline">
        <?php
        $hour = date('H');
        if ($hour >= 0 && $hour <= 11) {
          echo "<span class=''>สวัสดีตอนเช้า " . $_SESSION["user"]["firstname"];
        } else if ($hour >= 12 && $hour <= 17) {
          echo "<span class=''>สวัสดีตอนบ่าย " . $_SESSION["user"]["firstname"];
        } else if ($hour >= 18 && $hour <= 23) {
          echo "<span class=''>สวัสดีตอนเย็น " . $_SESSION["user"]["firstname"];
        }
        ?>
      </span>
      <span> | </span>
      <span class="text-muted d-block d-md-inline" id="weatherDiv"></span>
    </div>

    <hr>

    <div class="col-12 mb-3">
      <div class="card">
        <div class="card-header bg-label-secondary py-2 h4">
          Menu
        </div>
        <div class="card-body p-0 pt-3">
          <div class="row">
            <div class="col-6 col-md-2 text-center">
              <a href="/home/dashboard">
                <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/combo-chart--v1.png" alt="">
                <p class="text-secondary fs-5">Dashboard</p>
              </a>
            </div>

            <?php if (User::isUser()) : ?>
              <div class="col-6 col-md-2 text-center">
                <a href="/projects">
                  <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/to-do.png" alt="">
                  <p class="text-secondary fs-5">รายงานโครงการ</p>
                </a>
              </div>
            <?php endif; ?>

            <?php if (User::isDirector()) : ?>
              <div class="col-6 col-md-2 text-center">
                <a href="/indicators/approve">
                  <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/to-do.png" alt="">
                  <p class="text-secondary fs-5">ยืนยันคะแนน</p>
                </a>
              </div>
            <?php endif; ?>

            <?php if (User::isAdmin()) : ?>
              <!-- <div class="col-6 col-md-2 text-center">
                <a href="/users">
                  <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/user-group-man-man--v1.png" alt="">
                  <p class="text-secondary fs-5">จัดการผู้ใช้งาน</p>
                </a>
              </div> -->
              <div class="col-6 col-md-2 text-center">
                <a href="/quarter">
                  <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/overtime.png" alt="">
                  <p class="text-secondary fs-5">จัดการไตรมาส</p>
                </a>
              </div>
              <div class="col-6 col-md-2 text-center">
                <a href="/annouce">
                  <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/commercial.png" alt="">
                  <p class="text-secondary fs-5">จัดการประกาศ</p>
                </a>
              </div>
            <?php endif; ?>

            <div class="col-6 col-md-2 text-center">
              <a href="/publish">
                <img class="col-4 shortcut-logo" src="https://img.icons8.com/color/96/opened-folder.png" alt="">
                <p class="text-secondary fs-5">เอกสารเผยแพร่</p>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12 col-md-5 mb-3">
      <div class="card">
        <div class="card-header bg-label-warning py-2 h4">
          ประกาศ
        </div>
        <div class="scrollable-div">
          <?php
          foreach ($annouces as $key => $val) {
            $thaiDate = thaiDateFormat($val['start_date']);
            $dateDiff = date_diff(date_create($val['start_date']), date_create(date('Y-m-d')));
            $dateDiff = $dateDiff->format("%a");

            echo "<div class='annouceList py-2 px-3 " . ($val['pin'] ? "pinned" : "") . "'>
            <span class='ps-3 fs-6'>เรื่อง <b>$val[title]</b></span>" . " " . ($dateDiff < 3 ? "<img src='/assets/gif/new1.gif'>" : "") . " " . ($val['pin'] ? "<i class='bx bx-pin text-danger'></i>" : "") . "</br> 
            <span class='ps-3 fs-6'>$val[detail]</span></br>
            <span class='ps-3' style='font-size:0.7em;'>$thaiDate</span>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-7">
      <div class="card">
        <div class="card-header bg-label-primary py-2 h4">
          หน่วยงานที่มีการรายงานสูงสุด ปี <?= Budgetyear::getBudgetyearThai() ?>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <td class="text-dark fw-bold fs-5 text-center">อันดับ</td>
                <td class="text-dark fw-bold fs-5 text-start">หน่วยงาน</td>
                <td class="text-dark fw-bold fs-5 text-center">ร้อยละ</td>
                <td class="text-dark fw-bold fs-5 text-center">โครงการทั้งหมด</td>
                <td class="text-dark fw-bold fs-5 text-center">Q1</td>
                <td class="text-dark fw-bold fs-5 text-center">Q2</td>
                <td class="text-dark fw-bold fs-5 text-center">Q3</td>
                <td class="text-dark fw-bold fs-5 text-center">Q4</td>
              </tr>
            </thead>
            <tbody>
              <?php
              $colors = ["#FFD700", "#C0C0C0", "#CD7F32", "#233446", "#233446"];
              $html = "";

              usort($topReported, function ($a, $b) {
                return $b['percentile'] <=> $a['percentile'];
              });
              
              foreach ($topReported as $key => $val) {
                $html .= "<tr>
                <td class='text-dark fw-bold fs-5 text-center' style='width: 3%; color:" . $colors[$key] . " !important;'>" . ($key + 1) . "</td>
                <td class='text-dark fw-bold fs-5 text-start' style='width:20%;'>";
                if ($key == 0) {
                  $html .= "<i class='bx bx-medal' style='color:" . $colors[$key] . " !important;'></i> ";
                } elseif ($key == 1) {
                  $html .= "<i class='bx bx-medal' style='color:" . $colors[$key] . " !important;'></i> ";
                } elseif ($key == 2) {
                  $html .= "<i class='bx bx-medal' style='color:" . $colors[$key] . " !important;'></i> ";
                } else {
                  $html .= "";
                }

                $html .= "$val[division_abbr]</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:5%; color:" . $colors[$key] . " !important;'>" . number_format($val['percentile'], 2) . "%</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:20%;'>" . $val['count_project'] . "</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:2%;'>" . $val['q1_reported'] . "</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:2%;'>" . $val['q2_reported'] . "</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:2%;'>" . $val['q3_reported'] . "</td>
                <td class='text-dark fw-bold fs-5 text-center' style='width:2%;'>" . $val['q4_reported'] . "</td>
              </tr>";
              }
              echo $html;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-7">
      <div class="card">
        <div class="card-header bg-label-secondary py-2 h4">
          ปฏิทินการรายงาน
        </div>
        <div class="card-body pt-3">
          <div id="calendarDiv"></div>
        </div>

      </div>
    </div>

  </div>

</div>

<!-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.11/index.global.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    let quarters = <?php echo json_encode($quarters); ?>;
    let currentDate = new Date();
    let quarterLength = quarters.length;
    let endQuarterTitle = '';
    // console.log(quarters, currentDate);
    //compare current date with quarter end date; if curernt date is less than quarter end date, then break
    for (let i = 0; i < quarterLength; i++) {
      let quarterEndDate = new Date(quarters[i].end_date);
      if (currentDate < quarterEndDate) {
        // console.log(quarters[i].end_date);
        endQuarterTitle = quarters[i].end_date;
        break;
      }
    }

    initCalendar();
  });

  $(document).ready(function() {
    getWeather('weatherDiv');
  })

  function initCalendar() {
    let events = [
      {
        title: 'เริ่มไตรมาสที่ 1',
        start: '2024-10-01',
        color: '#71dd37',
        display: 'background'
      },
      {
        title: 'สิ้นสุดไตรมาสที่ 1',
        start: '2024-12-31',
        color: '#ff3e1d',
        display: 'background'
      },
      {
        title: 'เริ่มไตรมาสที่ 2',
        start: '2025-01-01',
        color: '#71dd37',
        display: 'background'
      },
      {
        title: 'สิ้นสุดไตรมาสที่ 2',
        start: '2025-03-31',
        color: '#ff3e1d',
        display: 'background'
      },
      {
        title: 'เริ่มไตรมาสที่ 3',
        start: '2025-04-01',
        color: '#71dd37',
        display: 'background'
      },
      {
        title: 'สิ้นสุดไตรมาสที่ 3',
        start: '2025-06-30',
        color: '#ff3e1d',
        display: 'background'
      },
      {
        title: 'เริ่มไตรมาสที่ 4',
        start: '2025-07-01',
        color: '#71dd37',
        display: 'background'
      },
      {
        title: 'สิ้นสุดไตรมาสที่ 4',
        start: '2025-09-30',
        color: '#ff3e1d',
        display: 'background'
      },
    ]
    var calendarEl = document.getElementById('calendarDiv');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'th',
      events: events,
      height: 500,
      //add text to fc-toolbar-chunk
    });
    calendar.render();
  }

  function getWeather(eleId) {
    const apiKey = ''; //insert your api key here
    //const location = 'Bangkok';
    if ("geolocation" in navigator) {

      navigator.geolocation.getCurrentPosition(function(position) {

        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        //round lat lon to 2 decimal places
        const lat = latitude.toFixed(2);
        const lon = longitude.toFixed(2);
        const apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}`;

        fetch(apiUrl)
          .then((response) => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
          })
          .then((data) => {
            // Parse the JSON response
            // console.log(data);

            // Extract weather information from the data object
            const temperature = data.main.temp;
            const weatherDescription = data.weather[0].description;

            // Convert temperature from Kelvin to Celsius
            let temperatureCelsius = temperature - 273.15;
            // Round temperature to full number
            temperatureCelsius = Math.round(temperatureCelsius);

            // Use the weather data as needed
            // console.log(`Temperature: ${temperatureCelsius}°C`);
            // console.log(`Weather: ${weatherDescription}`);

            //append weather data to div
            const weatherDiv = document.getElementById(eleId);
            weatherDiv.innerHTML = `
               อากาศวันนี้ 
               ${temperatureCelsius}°C
               <img width="70px" src="${weatherDetailIconLookup(weatherDescription)}" title="${weatherDescription}">
           `;
          })
          .catch((error) => {
            // console.error('Fetch error:', error);
          });

      }, function(error) {
        // Handle errors here
        switch (error.code) {
          case error.PERMISSION_DENIED:
            // console.error("User denied the request for geolocation.");
            break;
          case error.POSITION_UNAVAILABLE:
            // console.error("Location information is unavailable.");
            break;
          case error.TIMEOUT:
            // console.error("The request to get user location timed out.");
            break;
          case error.UNKNOWN_ERROR:
            // console.error("An unknown error occurred.");
            break;
        }
      });
    } else {
      // console.error("Geolocation is not available in this browser.");
    }
  }

  function weatherDetailIconLookup(weatherDetail) {
    // check day or night
    const hour = new Date().getHours();
    const isDayTime = hour > 6 && hour < 18;

    //return background from weather detail
    switch (weatherDetail) {
      case 'clear sky':
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/day.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/night.svg';
          break;
        }
      case 'few clouds':
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/cloudy-day-1.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/cloudy-night-2.svg';
          break;
        }
      case 'scattered clouds':
        return '/assets/svg/amchart-animated-icon/cloudy.svg';
        break;
      case 'broken clouds':
        return '/assets/svg/amchart-animated-icon/cloudy.svg';
        break;
      case 'rain':
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/rainy-1.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/rainy-5.svg';
          break;
        }
      case 'light rain':
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/rainy-1.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/rainy-4.svg';
          break;
        }
      case 'shower rain':
        return '/assets/svg/amchart-animated-icon/rainy-7.svg';
        break;
      case 'moderate rain':
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/rainy-1.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/rainy-6.svg';
          break;
        }
      case 'thunderstorm':
        return '/assets/svg/amchart-animated-icon/thunder.svg';
        break;
      default:
        if (isDayTime) {
          return '/assets/svg/amchart-animated-icon/day.svg';
          break;
        } else {
          return '/assets/svg/amchart-animated-icon/night.svg';
          break;
        }
    }
  }

  function thaiDateFormat(date) {
    let thaiMonths = {
      "01": "ม.ค.",
      "02": "ก.พ.",
      "03": "มี.ค.",
      "04": "เม.ย.",
      "05": "พ.ค.",
      "06": "มิ.ย.",
      "07": "ก.ค.",
      "08": "ส.ค.",
      "09": "ก.ย.",
      "10": "ต.ค.",
      "11": "พ.ย.",
      "12": "ธ.ค."
    };

    date = date.split("-");
    let year = parseInt(date[0]) + 543;
    let month = thaiMonths[date[1]];
    let day = date[2];

    return `${day} ${month} ${year}`;
  }
</script>
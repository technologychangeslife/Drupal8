var timelineVue = new Vue({
  el: '#timeline',
  created: function () {
    //console.log("Mounting interactive");
  },
  methods: {
    next: function () {
      //console.log("NEXT CLICKED")
    },
    previous: function () {
      //console.log("PREV CLICKED")
    },
    updateActiveDecade: function (decade) {
      var decadeData = this.getDecadeByLabel(decade);
      this.activeDecade = decadeData.label;
      this.activeDecadeTitle = decadeData.title;
      window.location.hash = decadeData.label;
    },
    jumpToDecade: function (decade) {
      this.updateActiveDecade(decade);
      var scrollToPoint = jQuery('#decade-' + decade).offset().top - parseInt(jQuery('.timeline-header', '#timeline').outerHeight()) + 1;
      this.smoothScroll(scrollToPoint);
    },
    getDecadeByLabel: function (decade) {
      var decadeData = null;
      this.decades.forEach(function (d, i) {
        if (d.label == decade) {
          decadeData = d;
          return;
        }
      });
      return decadeData;
    },
    smoothScroll: function (to) {
      // @see https://gist.github.com/james2doyle/5694700
      // easing functions http://goo.gl/5HLl8
      Math.easeInOutQuad = function (t, b, c, d) {
        t /= d / 2;
        if (t < 1) {
          return c / 2 * t * t + b
        }
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
      };
      Math.easeInCubic = function (t, b, c, d) {
        var tc = (t /= d) * t * t;
        return b + c * (tc);
      };
      Math.inOutQuintic = function (t, b, c, d) {
        var ts = (t /= d) * t,
          tc = ts * t;
        return b + c * (6 * tc * ts + -15 * ts * ts + 10 * tc);
      };
      // requestAnimationFrame for Smart Animating http://goo.gl/sx5sts
      var requestAnimFrame = (function () {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (callback) {
          window.setTimeout(callback, 1000 / 60);
        };
      })();
      var scrollTo = function (to, callback, duration) {
        // because it's so difficult to detect the scrolling element, just move them all
        function move(amount) {
          document.documentElement.scrollTop = amount;
          document.body.parentNode.scrollTop = amount;
          document.body.scrollTop = amount;
        }

        function position() {
          return document.documentElement.scrollTop || document.body.parentNode.scrollTop || document.body.scrollTop;
        }

        var start = position(),
          change = to - start,
          currentTime = 0,
          increment = 20;
        duration = (typeof (duration) === 'undefined') ? 500 : duration;
        var animateScroll = function () {
          // increment the time
          currentTime += increment;
          // find the value with the quadratic in-out easing function
          var val = Math.easeInOutQuad(currentTime, start, change, duration);
          // move the document.body
          move(val);
          // do the animation unless its over
          if (currentTime < duration) {
            requestAnimFrame(animateScroll);
          } else {
            if (callback && typeof (callback) === 'function') {
              // the animation is done so lets callback
              callback();
            }
          }
        };
        animateScroll();
      };
      scrollTo(to, null, 1500);
    }
  },
  data: {
    headerOffset: 109, // header height, to offset when scrolling
    activeDecadeTitle: "1951-1959",
    activeDecade: 1950,
    decades: [
      {
        label: 1950,
        title: "1951-1959",
        rows: [
          {

            image: "/themes/custom/cubic/interactive/images/1950/07_20_56-First-Cubic-BLDG-300.jpg",
            year: "1951",
            description: "Cubic Corporation is founded by Walter J. Zable at a small storefront in San Diego’s Point Loma. Its only product is a device for measuring the power of microwaves.",
          },
          {
            image: "/themes/custom/cubic/interactive/images/1950/WJZ-at-Desk-1961-Annual-Report-3337Aduo-300.jpg",
            year: "1959",
            description: "Cubic Corporation becomes a publicly traded company."
          }
        ]
      },
      {
        label: 1960,
        title: "1960-1969",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/1960/juliet-duo.jpg",
            year: "1960",
            description: "Cubic’s Geodetic Sequential Collation of Range satellite helps remap Planet Earth and increase accuracy of ballistic missiles from outer space."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1960/electrotape_duo.jpg",
            year: "1961",
            description: "Cubic becomes the world leader in land and off-shore surveying systems with Electrotape, Autotape and ARGO. <br/><br/>Cubic builds early trajectory tracking systems for the White Sands Missile Range and other military test sites, leading to the establishment of its Defense Applications business."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1960/Buildin1-50s.jpg",
            year: "1963",
            description: "Cubic moves into its first new building at Balboa Avenue and Ruffin Road in San Diego, California."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1960/Scoreboard-compositeduo.jpg",
            year: "1967",
            description: "Cubic builds country’s first most advanced electronic scoreboard, installed in San Diego Stadium."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1960/1969-elevator.jpg",
            year: "1969",
            description: "Cubic acquires U.S. Elevator. By adding a computer control, it grew from the 23rd largest elevator company to #3 before Cubic sold it to Thyssen in 1993."
          }
        ]
      },
      {
        label: 1970,
        title: "1970-1979",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/1970/PATCO_AFC_30084-duo.jpg",
            year: "1971",
            description: "Cubic enters transportation business with acquisition of small Los Angeles Company that evolves into Cubic Transportation Systems."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1970/80s_top_gun_poster-duo.jpg",
            year: "1973",
            description: "Cubic installs first “Top Gun” instrumented air combat training system at Marine Corps Air Station Yuma based on prior Cubic precision tracking systems."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1970/BART-AFC--interneg--9-1980--8-bit-26267-duo.jpg",
            year: "1974",
            description: "Cubic wins its first major Automated Fare Collection system contract for the Bay Area Rapid Transit system in San Francisco. Contracts with more major cities around the world to follow."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1970/Hong_Kong_59098-duo.jpg",
            year: "1976",
            description: "Cubic acquires a British based manufacturer of passenger gates for transit systems, leading to the company’s UK transportation subsidiary.Cubic wins its first Automated Fare Collection System contract oversees, providing system to Hong Kong Mass transit Railway Corporation."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1970/cts_queen_gates_77.jpg",
            year: "1979",
            description: "Cubic actively seeks more Automated Fare Collection business opportunities overseas by forming a jointventure company."
          },
        ]
      },
      {
        label: 1980,
        title: "1980-1989",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/1980/10_98-New-York-Bus-Depot-61218-1cc.jpg",
            year: "1980",
            description: "Cubic introduces a computerized bus fare box."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1980/ACMI-screen.jpg",
            year: "1981",
            description: "Cubic’s Air Combat Maneuvering Range system for U.S. Air Force in Europe becomes operational."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1980/London-Big-Ben-w-bus.jpg",
            year: "1985",
            description: "Cubic Transportation wins London’s Automated Fare Collection contract.<br/><br/>Cubic is awarded its first data link contract for the U.S. Army’s Joint Surveillance and Target Attack Radar System, a sophisticated battlefield management system."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1980/heil-w-soldier.jpg",
            year: "1987",
            description: "Cubic develops Personnel Locator System and Ground Collision Avoidance System.<br/><br/>Cubic acquires company that leads to the establishment of its Mission Support Services business.<br/><br/>Cubic Defense Applications incorporates as whollyowned subsidiary."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1980/JStars-duo.jpg",
            year: "1989",
            description: "Cubic’s Joint Surveillance Target Attack Radar System and Personnel Locator System products are used during Persian Gulf War.<br/><br/>Cubic installs the world’s first privately owned Air Combat Training Range extending over the North Sea."
          }
        ]
      },
      {
        label: 1990,
        title: "1990-1999",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/1990/2soldierontank.jpg",
            year: "1990",
            description: "Cubic wins a U.S. Army contract for its first ground combat training system, which is used around the globe to gain the edge in combat readiness. Cubic wins a contract for the first phase of Automated Fare Collection system in New York City."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/23SINGAPORE.jpg",
            year: "1991",
            description: "Cubic forms the Cubic Automatic Revenue Collection Group from Cubic Western Data and several smaller transportation acquisitions."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/Washington-metro-duo.jpg",
            year: "1993",
            description: "Cubic smart card technology is introduced in the Washington, DC. Metro area.<br/><br/>Cubic wins an Army contract to provide the Joint Readiness Training Center - Instrumentation System at Fort Polk, Louisiana.<br/><br/>With Cubic technology, Nellis Training Center/Air Warrior goes operational at Nellis Air Force Base, Nevada and Fort Irwin.<br/><br/>Cubic wins a contract for the Chicago Automated Fare Collection system."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/06_98Guangzhou-AFC-System-Opening-61147-8.jpg",
            year: "1994",
            description: "Cubic Automatic Revenue Collection Group wins Cubic’s first contract in China in the city of Guangzhou.<br/><br/>Cubic introduces Visual Interactive Surveillance and Target Acquisition."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/KITSpod.jpg",
            year: "1995",
            description: "Kadena Interim Training System contract awarded to Cubic.<br/><br/>Cubic wins contract to provide Multiple Integrated Laser Engagement Systems to the U.S. military."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/ACTR.jpg",
            year: "1996",
            description: "Cubic wins contract to create an upgraded Nellis Air Combat Training System.<br/><br/>Cubic conducts first demonstration of its Air Combat Training Rangeless System.<br/><br/>Cubic wins the Shanghai Metro Automated Fare Collection Contract."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/JRTC-FT-Polk.jpg",
            year: "1997",
            description: "Cubic Automatic Revenue Collection Group wins automated fare collection contracts in Bangkok and Vancouver.<br/><br/>Joint Readiness Training Center Instrumentation System becomes operational."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/MILES_Engagement.jpg",
            year: "1998",
            description: "A major Multiple Integrated Laser Engagement System 2000 production contract is awarded.<br/><br/>Cubic Automatic Revenue Collection Group is renamed Cubic Transportation Systems."
          },
          {
            image: "/themes/custom/cubic/interactive/images/1990/Smart_cards_reader.jpg",
            year: "1999",
            description: "Cubic develops the first ISO 14443 compliant multi-protocol smart card reader.<br/><br/>Cubic Transportation Systems launches smart cards for rail in Washington, D.C.<br/><br/>Cubic wins AWES, the first international combat training center in the U.K."
          }
        ]
      },
      {
        label: 2000,
        title: "2000-2009",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/2000/MASTHeadduo.jpg",
            year: "2000",
            description: "Cubic wins contract to provide a data link for the United Kingdom’s Airborne Stand-Off Radar System.<br/><br/>Cubic Acquires Oscmar in Auckland, NZ establishing its gateway Asia Pacific training markets."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/50Logoduo.jpg",
            year: "2001",
            description: "Cubic celebrates its 50th anniversary.<br/><br/>Cubic becomes the prime mission support contractor for the Joint Readiness Training Center at Fort Polk, Louisiana, the U.S. Army’s premier combat training venue."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/SmarTripcardreader.jpg",
            year: "2002",
            description: "The Chicago Transit Authority integrates smart cards into its automated fare collection system.<br/><br/>Cubic rolls out the first fully operational intermodal systems in the U.S. (Washington, D.C. and Chicago)."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/EST20Collective20220soldiers.jpg",
            year: "2003",
            description: "Cubic acquires an Orlando-based manufacturer of virtual training systems for the U.S. military.<br/><br/>Cubic wins a 10-year contract for the U.S. military’s fifth generation air combat maneuvering system."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/oyster-card-3.jpg",
            year: "2004",
            description: "Cubic develops the largest multimodal system in Europe, the London Oyster&#174; Card."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/JSF-duo-27_11540_F35.jpg",
            year: "2006",
            description: "Lockheed Awards Joint Strike Fighter ACMI contract to Cubic to equip every F-35 aircraft.<br/><br/>Cubic’s 5th generation air combat training system becomes operational at Naval Air Station Key West and Luke Air Force Base."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/videoscoutput-1.jpg",
            year: "2007",
            description: "Cubic begins development of miniature common data links for unmanned air systems.<br/><br/>Cubic wins new 10 year support contract at the Joint Readiness Training Center, USA’s primary training venue for troops deploying to Iraq and Afghanistan.<br/><br/> Lockheed Martin awards contract to Cubic to design and integrate the air combat training system for the F-35 Lightning II Joint Strike Fighter.<br/><br/>Cubic is first to deploy an American Public Transportation Association open standard transit revenue management system in North America."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/Atlanta-Breeze-touch.jpg",
            year: "2008",
            description: "Cubic Transportation rolls out first wholly contactless fare system in the U.S. (Atlanta).<br/><br/>Cubic conducts successful mobile ticketing trials in San Francisco.<br/><br/>Cubic significantly expands Mission Support Services business unit with major acquisition."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2000/Cubic-at-NYSE-opening-Bell-March-5-2009.jpg",
            year: "2009",
            description: "Cubic celebrates 50 years as a publicly traded company. First listed on the American Stock Exchange, Cubic is now listed on the New York Stock Exchange under the symbol NYSE: CUB."
          }
        ]
      },
      {
        label: 2010,
        title: "2010-2019",
        rows: [
          {
            image: "/themes/custom/cubic/interactive/images/2010/Global-tracking.jpg",
            year: "2010",
            description: "Cubic Corporation revenues exceed $1 billion for the first time ever.<br/><br/>Cubic diversifies with the acquisition of global asset tracking and cyber security businesses.<br/><br/>Cubic wins nextgeneration Multiple Integrated Laser Engagement System contracts with the U.S. Army.<br/><br/>Cubic receives a contract to provide Greater Sydney’s electronic ticketing system. <br/><br/>Cubic launches a live multi-operator mobile ticketing program in Frankfurt, Germany."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Barclaycard-Oyster-23.jpg",
            year: "2011",
            description: "Cubic Transportation Systems is selected by the Chicago Transit Authority to integrate, deliver, operate and maintain the agency’s next-generation open payments system that will accept bank cards, and ultimately, mobile phone payments.<br/><br/>Cubic is first in the world to deploy a transit agency-branded prepaid all-contactless card, in partnership with the Port Authority Transit Corporation, owned and operated by the Delaware River Port Authority.<br/><br/>Cubic is recognized by Aviation Week as the number 1 performing company in the world with revenues between $1 billion and $5 billion."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Olympics-duo.jpg",
            year: "2012",
            description: "Cubic’s CEO Walter J. Zable passes at age 97 as the world’s oldest CEO.<br/><br/>Cubic’s fare collection systems in London carries 4.4 million passengers to the 2012 Olympic Games, setting a public transportation record for most passengers travelled."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Traffic-300px.jpg",
            year: "2013",
            description: "Cubic acquires NEK Advance Security Group to expand Special Operations Forces Training Business.<br/><br/>Cubic forms Intelligent Transport Management Solutions from acquisition of UK based Serco company. The newly formed company provides CTS with enhanced development of complex traffic management systems and road user travel intelligence capabilities in the UK.<br/><br/>Cubic acquires PS Management Consultants, an Australian defense firm that provides Cubic with a strong foothold in training ranges, communications and project management in Australia.<br/><br/>Cubic forms Range Design Solutions business after acquisition of UK based company AIS. Cubic Range Design Solutions is a leading supplier of live fire specialized range facilities, virtual simulation product, and engineering design for counter terrorism, law enforcement and military forces worldwide."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/CMS-Image_Army.jpg",
            year: "2014",
            description: "Cubic acquires Intific, an Austin-based company that specializes in next-generation software to advance game-based training.<br/><br/>Cubic launches Urban Insights Associates, a professional services firm introducing the application of big data tools and data science-based deep analytics techniques to public transportation agencies, road authorities and municipalities.<br/><br/>Cubic wins Transport for London’s Electra contract, which has an ultimate value of $1 billion over 10 years – the largest contract in Cubic’s history.<br/><br/>Cubic acquires DTECH Labs, a leading provider of advanced communications solutions to expand Cubic’s specialized command, control, communications, computers, intelligence, surveillance and reconnaissance (C4ISR) business."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/GATR3Amigos.jpg",
            year: "2015",
            description: "Cubic combines its two defense operating segments into a single segment called Cubic Global Defense.<br/><br/>Cubic expands C4ISR portfolio and secure and expeditionary communications capabilities. Acquires TeraLogics, a specialized provider of industry specific solutions for live and archived streaming video."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/16-NextAgent-4012.jpg",
            year: "2016",
            description: "Abellio and Cubic Trial U.K.’s First “Video Ticket Office” NextAgent at Stansted Airport. Creation of Cubic Mission Solutions business division to focus on C4ISR solutions.<br/><br/>Cubic Wins Contract to Supply Fare Collection Equipment for Singapore Land Transport Authority’s Future Rail Line.<br/><br/>Cubic acquires Vocality International, expanding its C4ISR portfolio."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/MetroCard.jpg",
            year: "2017",
            description: "Cubic wins $539.5 million contract from New York MTA to replace iconic MetroCard System with new world-class fare payment system.<br/><br/>Cubic’s GATR breaks ground on a new expanded facility in Huntsville, AL to accommodate its rapidly growing production and operations departments.<br/><br/>Cubic acquires Deltenna Limited to strengthen its wireless communication capabilities for tactical and training applications."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/isr-systems.jpg",
            year: "2018",
            description: "Cubic Corporation’s subsidiary, ISR Systems, Inc., acquired the assets of Shield Aviation, a provider of autonomous aircraft systems (AAS) for intelligence, surveillance and reconnaissance (ISR) services to expand Cubic Mission Solutions’ (CMS) C4ISR portfolio.<br/><br/>MotionDSP is acquired to become part of the Cubic Mission Solutions’ (CMS) business unit. MotionDSP is a leading provider of advanced image processing software for public safety, security and government applications."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Cubic-Trafficware-2018.jpg",
            year: "2018",
            description: "Trafficware is acquired to become part of the Cubic Transportation Systems’ (CTS) business unit. Trafficware is a leading provider of intelligent traffic solutions for the transportation industry, for approximately $235.7 million in cash, subject to customary adjustments."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Gridsmart.jpg",
            year: "2019",
            description: "Cubic acquires GRIDSMART Technologies, Inc., a market-leading, technology-driven business with a differentiated video tracking offering in the Intelligent Traffic Systems (ITS) market."
          },
          {
            image: "/themes/custom/cubic/interactive/images/2010/Nuvotronics.jpg",
            year: "2019",
            description: "Cubic acquires Nuvotronics, a disruptive technology provider of microfabricated radio frequency (RF) products to enhance Cubic Mission Solutions’ market-leading portfolio."
          }
        ]
      }
    ]
  }
});

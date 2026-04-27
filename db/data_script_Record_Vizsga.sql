/*------------------------------------------- REKORD TÍPUSOK ----------------------------------------------*/
insert into record_type(type_name)
    values
    	("Album"),
    	("EP"),
    	("Single")
;

/*------------------------------------------- ADMIN FELHASZNÁLÓ -------------------------------------------*/
insert into `user`(id,`name`,password_hash,email,phone,`role`)
    values(1,"admin","admin1234","admin@record.hu","06309353729","admin"); /*Password is generated on migration (password: admin1234)*/

/*------------------------------------------- SZERZŐK ------------------------------------------------------*/
insert into artist(`name`,active_since,nationality,`url`,is_group, icon_path,cover_path)
    values
        ("Metallica",                       "1981", "usa",  "https://www.metallica.com",            1,  "Artists/MetallicaIcon.jpg",                  "Artists/MetallicaBanner.jpg"),                  /*1*/
        ("Michael Jackson",                 "1964", "usa",  "https://www.michaeljackson.com",       0,  "Artists/MichaelJacksonIcon.jpg",             "Artists/MichaelJacksonBanner.jpg"),             /*2*/
        ("Playboi Carti",                   "2015", "usa",  "https://soundcloud.com/playboicarti",  0,  "Artists/PlayboiCartiIcon.jpg",               "Artists/PlayboiCartiBanner.jpg"),               /*3*/
        ("1000 Eyes",                       "2021", null,   "https://thousandeyes.bandcamp.com",    0,  "Artists/1000EyesIcon.jpg",                   "Artists/1000EyesBanner.jpg"),                   /*4*/
        ("Masayoshi Takanaka",              "1970", "jpn",  "https://takanaka.com",                 0,  "Artists/MasayoshiTakanakaIcon.jpg",          "Artists/MasayoshiTakanakaBanner.jpg"),          /*5*/
        ("Kárpátia",                        "2003", "hun",  "https://www.karpatiazenekar.hu",       0,  "Artists/KarpatiaIcon.jpg",                   "Artists/KarpatiaBanner.jpg"),                   /*6*/
        ("Korda György",                    "1958", "hun",  null,                                   0,  "Artists/KordaGyorgyIcon.jpg",                "Artists/KordaGyorgyBanner.jpg"),                /*7*/
        ("The Neighbourhood",               "2011", "usa",  "https://tour.thenbhd.com",             1,  "Artists/TheNeighbourhoodIcon.jpg",           "Artists/TheNeighbourhoodBanner.jpg"),           /*8*/
        ("Astrophysics",                    "2018", "bra",  "https://astrophysicsbrazil.bandcamp.com/music", 1, "Artists/AstrophysicsIcon.jpg",        "Artists/AstrophysicsBanner.jpg"),              /*9*/
        ("TV Girl",                         "2013", "usa",  "https://tvgirl.bandcamp.com",          1,  "Artists/TVGirlIcon.jpg",                     "Artists/TVGirlBanner.jpg"),                     /*10*/
        ("Jordana",                         "2018", "usa",  "https://jordana.cool",                 0,  "Artists/JordanaIcon.jpg",                    "Artists/JordanaBanner.jpg"),                    /*11*/
        ("Julie",                           "2020", "usa",  "https://julie.bandcamp.com",           1,  "Artists/JulieIcon.jpg",                      "Artists/JulieBanner.jpg"),                      /*12*/
        ("WEDNESDAY CAMPANELLA",            "2013", "jpn",  null,                                   1,  "Artists/WednesdayCampanellaIcon.jpg",        "Artists/WednesdayCampanellaBanner.jpg"),        /*13*/
        ("MASS OF THE FERMENTING DREGS",    "2006", "jpn",  "https://www.motfd.com/",               1,  "Artists/MassOfTheFermentingDregsIcon.jpg",   "Artists/MassOfTheFermentingDregsBanner.jpg"),   /*14*/
        ("mollywood",                       "2024", "hun",  "https://astromusic.hu/band/mollywood/",0,  "Artists/MollywoodIcon.jpg",                  "Artists/MollywoodBanner.jpg"),                  /*15*/
        ("Irina",                           "2023", "hun",  "https://soundcloud.com/edina-nagy-865719925",0,"Artists/IrinaIcon.jpg",                  "Artists/IrinaBanner.jpg"),                      /*16*/
        ("The Marías",                      "2017", "pri",  "https://www.themarias.us",             1,  "Artists/TheMariasIcon.jpg",                  "Artists/TheMariasBanner.jpg"),                  /*17*/
        ("Broken Social Scene",             "2001", "can",  "https://drop.cobrand.com/d/BrokenSocialScene/BrokenSocialScene", 1, "Artists/BrokenSocialSceneIcon.jpg", "Artists/BrokenSocialSceneBanner.jpg"), /*18*/
        ("jschlatt",                        "2024", "usa",  null,                                   0,  "Artists/JschlattIcon.jpg",                   "Artists/JschlattBanner.jpg"),                   /*19*/
        ("Luca Maxim",                      "2019", "geo",  "https://soundcloud.com/lucamaxim",     0,  "Artists/LucaMaximIcon.jpg",                  "Artists/LucaMaximBanner.jpg"),                  /*20*/
        ("Nirvana",                         "1989", "usa",  "https://www.nirvana.com",              1,  "Artists/NirvanaIcon.jpg",                    "Artists/NirvanaBanner.jpg"),                    /*21*/
        ("Tyler, The Creator",              "2011", "usa",  "https://soundcloud.com/tylerthecreatorofficial", 0, "Artists/TylerTheCreatorIcon.jpg",   "Artists/TylerTheCreatorBanner.jpg"),            /*22*/
        ("Frank Sinatra",                   "1935", "usa",  "https://www.sinatra.com",              0, "Artists/FrankSinatraIcon.jpg",                "Artists/FrankSinatraBanner.jpg"),               /*23*/
        ("The Cranberries",                 "2009", "irl",  "https://www.cranberries.com",          1, "Artists/TheCranberriesIcon.jpg",              "Artists/TheCranberriesBanner.jpg"),             /*24*/
        ("bôa",                             "1993", "gbr",  "https://www.boaukofficial.com",        1, "Artists/boaIcon.jpg",                         "Artists/boaBanner.jpg"),                       /*25*/
        ("what is your name?",              "2022", "can",  "https://whatisyourname.bandcamp.com/music", 0, "Artists/WhatIsYourNameIcon.jpg",         "Artists/WhatIsYourNameBanner.jpg")              /*26*/

    ;
/*------------------------------------------- ALBUMOK ------------------------------------------------------*/

insert into `record`(`name`,type_id,release_year,`length`,file_path)
    values
        ("Master Of Puppets",1,"1986",8,"Records/MasterOfPuppets.jpg"), /*1*/
        ("Ride The Lightning",1,"1984",8,"Records/RideTheLightning.jpg"), /*2*/
        ("Reload",1,"1997",13,"Records/Reload.jpg"), /*3*/
        ("Bad",1,"1987",9,"Records/Bad.jpg"), /*4*/
        ("Dangerous",1,"1991",9,"Records/Dangerous.jpg"), /*5*/
        ("Whole Lotta Red",1,"2020",24,"Records/WholeLottaRed.jpg"), /*6*/
        ("Die Lit",1,"2018",19,"Records/DieLit.jpg"), /*7*/
        ("MUSIC",1,"2025",30,"Records/MUSIC.jpg"), /*8*/
        ("1000 Eyes", 1, "2021", 13,"Records/1000Eyes.jpg"), /*9*/
        ("Duality", 1, "2024", 15,"Records/Duality.jpg"), /*10*/
        ("SIGNALIS: MEMORIES", 1, "2024", 7,"Records/SignalisMemories.jpg"), /*11*/
        ("THE RAINBOW GOBLINS",1,"1981",14,"Records/TheRainbowGoblins.jpg"), /*12*/
        ("FINGER DANCIN'",2,"2006",4,"Records/FingerDancin.jpg"), /*13*/
        ("BRASILIAN SKIES",1,"1978",8,"Records/BrasilianSkies.jpg"), /*14*/
        ("ALL OF ME",1,"2006",14,"Records/AllOfMe.jpg"), /*15*/
        ("Bátraké a szerencse",1,"2014",11,"Records/BatrakeASzerencse.jpg"), /*16*/
        ("A Száműzött",1,"2013",13,"Records/ASzamuzott.jpg"), /*17*/
        ("Napfény kell a világnak - Tegnap és ma",1,"2005",15,"Records/NapfenyKellAVilagnakTegnapEsMa.jpg"), /*18*/
        ("(((((ultraSOUND)))))", 1,"2025",15,"Records/UltraSound.jpg"), /*19*/
        ("Hard To Imagine The Neighbourhood Ever Changing", 1, "2018", 21,"Records/HardToImagineTheNeighbourhoodEverChanging.jpg"), /*20*/
        ("Thank you,", 3, "2012", 2,"Records/Thankyou.jpg"), /*21*/
        ("HOPE LEFT ME", 1, "2022", 12,"Records/HopeLeftMe.jpg"), /*22*/
        ("Who Really Cares", 1, "2016", 10,"Records/WhoReallyCares.jpg"), /*23*/
        ("Fauxllennium", 1,"2024",7,"Records/Fauxllennium.jpg"), /*24*/
        ("Summer's Over", 1, "2021", 7,"Records/SummersOver.jpg"), /*25*/
        ("flutter", 3, "2020", 1,"Records/Flutter.jpg"), /*26*/
        ("starjump/kit", 3, "2020", 2,"Records/StarjumpKit.jpg"), /*27*/
        ("pushing daisies", 2, "2021", 6,"Records/PushingDaisies.jpg"), /*28*/
        ("my anti-aircraft friend", 1, "2024", 10,"Records/MyAntiAircraftFriend.jpg"), /*29*/
        ("Kawaii girl", 1, "2025", 8,"Records/KawaiiGirl.jpg"), /*30*/
        ("POP DELIVERY", 1, "2024", 8,"Records/PopDelivery.jpg"), /*31*/
        ("Summer Time Ghost", 3, "2025", 1,"Records/SummerTimeGhost.jpg"), /*32*/
        ("World Is Yours", 2, "2009", 6, "Records/WorldIsYours.jpg"), /*33*/
        ("MASS OF THE FERMENTING DREGS", 2, "2008", 6, "Records/MassOfTheFermentingDregs.jpg"), /*34*/
        ("Larissza Radio", 1, "2025", 9, "Records/LarisszaRadio.jpg"), /*35*/
        ("Europa", 1, "2025", 11, "Records/Europa.jpg"), /*36*/
        ("gyógynövény", 3, "2023", 1, "Records/Gyogynoveny.jpg"), /*37*/
        ("dohányozni tilos", 1, "2024", 9, "Records/DohanyozniTilos.jpg"), /*38*/
        ("halovány", 3, "2025", 1, "Records/Halovany.jpg"), /*39*/
        ("Submarine", 1, "2024", 14, "Records/Submarine.jpg"), /*40*/
        ("CINEMA", 1, "2021", 13, "Records/Cinema.jpg"), /*41*/
        ("No One Noticed", 3, "2024", 1, "Records/NoOneNoticed.jpg"), /*42*/
        ("Feel Good Lost", 1, "2001", 12, "Records/FeelGoodLost.jpg"),/*43*/
        ("Anthems For A Seventeen Year-Old Girl", 3, "2002", 1, "Records/AnthemsForASeventeenYearOldGirl.jpg"), /*44*/
        ("You Forgot It In People", 1, "2013", 13, "Records/YouForgotItInPeople.jpg"), /*45*/
        ("Bee Hives", 1, "2004", 9, "Records/BeeHives.jpg"), /*46*/
        ("Broken Social Scene", 1, "2005", 14, "Records/BrokenSocialScene.jpg"), /*47*/
        ("French Exit", 1, "2014", 12, "Records/FrenchExit.jpg"), /*48*/
        ("The Night in Question: French Exit Outtakes", 1, "2020", 8, "Records/TheNightInQuestion.jpg"), /*49*/
        ("Grapes Upon The Vine", 1, "2023", 12, "Records/GrapesUponTheVine.jpg"), /*50*/
        ("Death of a Party Girl", 1, "2018", 10, "Records/DeathOfAPartyGirl.jpg"),/*51*/
        ("A Very 1999 Christmas", 1, "2024", 8, "Records/AVery1999Christmas.jpg"),/*52*/
        ("A Very 1999 Christmas (Deluxe)", 1, "2025", 11, "Records/AVery1999ChristmasDeluxe.jpg"), /*53*/
        ("i can't feel a thing", 3, "2026", 1, "Records/ICantFeelAThing.jpg"),/*54*/
        ("Mr. Worldwide", 3, "2025", 1, "Records/MrWorldwide.jpg"), /*55*/
        ("Azerbaijan Technology", 3, "2025", 1, "Records/AzerbaijanTechnology.jpg"), /*56*/
        ("Nearly Cried of Happiness", 1, "2023", 13, "Records/NearlyCriedOfHappiness.jpg"), /*57*/
        ("Bleach", 1, "1989", 13, "Records/Bleach.jpg"), /*58*/
        ("Nevermind", 1, "1991", 13, "Records/Nevermind.jpg"), /*59*/
        ("In Utero", 1, "1993", 12, "Records/InUtero.jpg"), /*60*/
        ("MTV Unplugged In New York", 1, "1994", 14, "Records/MTVUnpluggedInNewYork.jpg"), /*61*/
        ("Incesticide", 1, "1992", 15, "Records/Incesticide.jpg"), /*62*/
        ("DON'T TAP THE GLASS", 1, "2025", 10, "Records/DontTapTheGlass.jpg"), /*63*/
        ("CHROMAKOPIA", 1, "2024", 14, "Records/Chromakopia.jpg"), /*64*/
        ("CALL ME IF YOU GET LOST: The Estate Sale", 1, "2023", 24 ,"Records/CallMeIfYouGetLostTheEstateSale.jpg"), /*65*/
        ("CALL ME IF YOU GET LOST", 1, "2021", 16, "Records/CallMeIfYouGetLost.jpg"), /*66*/
        ("IGOR", 1, "2019", 12, "Records/Igor.jpg"), /*67777777777777777777777777777777 bro*/
        ("Flower Boy", 1, "2017", 14, "Records/FlowerBoy.jpg"), /*68*/
        ("Cherry Bomb", 1, "2015", 13, "Records/CherryBomb.jpg"), /*69*/
        ("Wolf", 1, "2013", 18, "Records/Wolf.jpg"), /*70*/
        ("That's Life", 1 , "1966", 10, "Records/ThatsLife.jpg"), /*71*/
        ("My Way", 1 , "1969", 12, "Records/MyWay.jpg"), /*72*/
        ("Everybody Else Is Doing It, So Why Can't We?", 1 , "1993", 12, "Records/EverybodyElseIsDoingItSoWhyCantWe.jpg"), /*73*/
        ("No Need To Argue", 1 , "1994", 13, "Records/NoNeedToArgue.jpg"), /*74*/
        ("Twilight", 1 , "2001", 14, "Records/Twilight.jpg"), /*75*/
        ("Whiplash", 3 , "2024", 4, "Records/Whiplash.jpg"), /*76*/
        ("the now now and never", 1, "2022", 8, "Records/TheNowNowAndNever.jpg"), /*77*/
        ("My Name Is...", 1, "2023", 9, "Records/MyNameIs.jpg"), /*78*/
        ("Rabbit EP", 2, "2023", 3, "Records/RabbitEP.jpg"), /*79*/
        ("beyond old names, everyone's songs.", 1, "2023", 12, "Records/BeyondOldNamesEveryonesSongs.jpg"), /*80*/
        ("1 Side Rd", 2, "2022", 4, "Records/1SideRd.jpg") /*81*/


    ;
/*------------------------------------------- SZERZŐK - ALBUMOK KÖTÉS---------------------------------------*/

insert into artist_record(artist_id,record_id,`role`)
    values
        /*Metallica*/
        (1,1,"producer"),
        (1,2,"producer"),
        (1,3,"producer"),
        /*Michael Jackson*/
        (2,4,"producer"),
        (2,5,"producer"),
        /*playboi carti*/
        (3,6,"producer"),
        (3,7,"producer"),
        (3,8,"producer"),
        /*1000 eyes*/
        (4,9,"producer"),
        (4,10,"producer"),
        (4,11,"producer"),
        /*takanaka*/
        (5,12,"producer"),
        (5,13,"producer"),
        (5,14,"producer"),
        (5,15,"producer"),
        /*karpatia*/
        (6,16,"producer"),
        (6,17,"producer"),
        /*Korda György*/
        (7,18,"producer"),
        /*The neighbourhood*/
        (8,19,"producer"),
        (8,20,"producer"),
        (8,21,"producer"),
        /*Astrophysics*/
        (9,22,"producer"),
        /*tv girl*/
        (10,23,"producer"),
        (10,24,"producer"),
        (10,48,"producer"),
        (10,49,"producer"),
        (10,50,"producer"),
        (10,51,"producer"),
        /*jordana*/
        (11,25,"producer"),
        (10,25,"featured"),
        /*julie*/
        (12,26,"producer"),
        (12,27,"producer"),
        (12,28,"producer"),
        (12,29,"producer"),
        /*WEDNESDAY CAMPANELLA*/
        (13,30,"producer"),
        (13,31,"producer"),
        (13,32,"producer"),
        /*MASS OF THE FERMENTING DREGS*/
        (14,33,"producer"),
        (14,34,"producer"),
        /*mollywood*/
        (15,35,"producer"),
        (15,36,"producer"),
        /*Irina*/
        (16,37,"producer"),
        (16,38,"producer"),
        (16,39,"producer"),
        /*The Marias*/
        (17,40,"producer"),
        (17,41,"producer"),
        (17,42,"producer"),
        /*Broken Social Scene*/
        (18,43,"producer"),
        (18,44,"producer"),
        (18,45,"producer"),
        (18,46,"producer"),
        (18,47,"producer"),
        /*jschlatt*/
        (19,52,"producer"),
        (19,53,"producer"),
        /*Luca Maxim*/
        (20,54,"producer"),
        (20,55,"producer"),
        (20,56,"producer"),
        (20,57,"producer"),
        /*Nirvana*/
        (21,58,"producer"),
        (21,59,"producer"),
        (21,60,"producer"),
        (21,61,"producer"),
        (21,62,"producer"),
        /*Tyler the creator*/
        (22,63,"producer"),
        (22,64,"producer"),
        (22,65,"producer"),
        (22,66,"producer"),
        (22,67,"producer"),
        (22,68,"producer"),
        (22,69,"producer"),
        (22,70,"producer"),
        /*Frank Sinatra*/
        (23,71,"producer"),
        (23,72,"producer"),
        /*The Cranberries*/
        (24,73,"producer"),
        (24,74,"producer"),
        /*boa*/
        (25,75,"producer"),
        (25,76,"producer"),
        /*what is your name?*/
        (26,77,"producer"),
        (26,78,"producer"),
        (26,79,"producer"),
        (26,80,"producer"),
        (26,81,"producer")

        
    ;
/*ARTIST_ID - RECORD_ID*/
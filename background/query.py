class mysql:
	data_users = {
		'SCPR' : ('''INSERT INTO `SCPR_data_users_{dimension}`( '''
			''' `date`, `TotalMembersThisWeek`, `KPI_TotalMembersKnownToMIP`, `CameToSiteThroughEmail`, `KPI_TotalEmailSubscribersKnownToMIP`, `KPI_PercentKnownSubsWhoCame`, `NewEmailSubscribers`, `TotalDonorsThisWeek`, `KPI_TotalDonorsKnownToMIP`, `Duplicated_CameThroughEmailPlusDonors`, `Unduplicated_TotalUsersKPI`, `Duplicated_Database_CameThroughEmailPlusDonors`, `Unduplicated_Database_TotalUsersKPI`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			''' `TotalMembersThisWeek` = %s, `KPI_TotalMembersKnownToMIP` = %s, `CameToSiteThroughEmail` = %s, `KPI_TotalEmailSubscribersKnownToMIP` = %s, `KPI_PercentKnownSubsWhoCame` = %s, `NewEmailSubscribers` = %s, `TotalDonorsThisWeek` = %s, `KPI_TotalDonorsKnownToMIP` = %s, `Duplicated_CameThroughEmailPlusDonors` = %s, `Unduplicated_TotalUsersKPI` = %s, `Duplicated_Database_CameThroughEmailPlusDonors` = %s, `Unduplicated_Database_TotalUsersKPI` = %s'''),
		'TT' : ('''INSERT INTO `TT_data_users_{dimension}`( '''
			''' `date`, `TotalMembersThisWeek`, `KPI_TotalMembersKnownToMIP`, `CameToSiteThroughEmail`, `KPI_TotalEmailSubscribersKnownToMIP`, `KPI_PercentKnownSubsWhoCame`, `KPI_NewEmailSubscribers`, `TotalDonorsThisWeek`, `KPI_TotalDonorsKnownToMIP`, `Duplicated_MembersPlusCameThroughEmailPlusDonors`, `Unduplicated_TotalUsersKPI`, `Duplicated_Database_MembersPlusCameThroughEmailPlusDonors`, `Unduplicated_Database_TotalUsersKPI`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			''' `TotalMembersThisWeek` = %s, `KPI_TotalMembersKnownToMIP` = %s, `CameToSiteThroughEmail` = %s, `KPI_TotalEmailSubscribersKnownToMIP` = %s, `KPI_PercentKnownSubsWhoCame` = %s, `KPI_NewEmailSubscribers` = %s, `TotalDonorsThisWeek` = %s, `KPI_TotalDonorsKnownToMIP` = %s, `Duplicated_MembersPlusCameThroughEmailPlusDonors` = %s, `Unduplicated_TotalUsersKPI` = %s, `Duplicated_Database_MembersPlusCameThroughEmailPlusDonors` = %s, `Unduplicated_Database_TotalUsersKPI` = %s'''),
		'WW' : ('''INSERT INTO `WW_data_users_{dimension}`( '''
			''' `date`, `TotalMembersThisWeek`, `KPI_TotalMembersKnownToMIP`, `CameToSiteThroughEmail`, `KPI_TotalEmailSubscribersKnownToMIP`, `KPI_PercentKnownSubsWhoCame`, `KPI_NewEmailSubscribers`, `TotalDonorsThisWeek`, `KPI_TotalDonorsKnownToMIP`, `Duplicated_MembersPlusCameThroughEmailPlusDonors`, `Unduplicated_TotalUsersKPI`, `Duplicated_Database_MembersPlusCameThroughEmailPlusDonors`, `Unduplicated_Database_TotalUsersKPI`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			''' `TotalMembersThisWeek` = %s, `KPI_TotalMembersKnownToMIP` = %s, `CameToSiteThroughEmail` = %s, `KPI_TotalEmailSubscribersKnownToMIP` = %s, `KPI_PercentKnownSubsWhoCame` = %s, `KPI_NewEmailSubscribers` = %s, `TotalDonorsThisWeek` = %s, `KPI_TotalDonorsKnownToMIP` = %s, `Duplicated_MembersPlusCameThroughEmailPlusDonors` = %s, `Unduplicated_TotalUsersKPI` = %s, `Duplicated_Database_MembersPlusCameThroughEmailPlusDonors` = %s, `Unduplicated_Database_TotalUsersKPI` = %s''')
	}
	data_stories = {
		'SCPR' : ('''INSERT INTO `SCPR_data_stories_{dimension}`( '''
			'''`date`, `path_article_md5`, `Page_Path`, `Article`, `Pageviews`, `Scroll_Start`, `Scroll_25`, `Scroll_50`, `Scroll_75`, `Scroll_100`, `Scroll_Supplemental`, `Scroll_End`, `Time_15`, `Time_30`, `Time_45`, `Time_60`, `Time_75`, `Time_90`, `Comments`, `Republish`, `Emails`, `Tweets`, `Facebook_Recommendations`, `Tribpedia_Related_Clicks`, `Related_Clicks`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`Page_Path` = %s, `Article` = %s, `Pageviews` = %s, `Scroll_Start` = %s, `Scroll_25` = %s, `Scroll_50` = %s, `Scroll_75` = %s, `Scroll_100` = %s, `Scroll_Supplemental` = %s, `Scroll_End` = %s, `Time_15` = %s, `Time_30` = %s, `Time_45` = %s, `Time_60` = %s, `Time_75` = %s, `Time_90` = %s, `Comments` = %s, `Republish` = %s, `Emails` = %s, `Tweets` = %s, `Facebook_Recommendations` = %s, `Tribpedia_Related_Clicks` = %s, `Related_Clicks` = %s'''),
		'TT' : ('''INSERT INTO `TT_data_stories_{dimension}`( '''
			'''`date`, `path_article_md5`, `Combo_URL`, `Article`, `Pageviews`, `Scroll_Start`, `Scroll_25`, `Scroll_50`, `Scroll_75`, `Scroll_100`, `Scroll_Supplemental`, `Scroll_End`, `Time_15`, `Time_30`, `Time_45`, `Time_60`, `Time_75`, `Time_90`, `Comments`, `Republish`, `Emails`, `Tweets`, `Facebook_Recommendations`, `Tribpedia_Related_Clicks`, `Related_Clicks`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`Combo_URL` = %s, `Article` = %s, `Pageviews` = %s, `Scroll_Start` = %s, `Scroll_25` = %s, `Scroll_50` = %s, `Scroll_75` = %s, `Scroll_100` = %s, `Scroll_Supplemental` = %s, `Scroll_End` = %s, `Time_15` = %s, `Time_30` = %s, `Time_45` = %s, `Time_60` = %s, `Time_75` = %s, `Time_90` = %s, `Comments` = %s, `Republish` = %s, `Emails` = %s, `Tweets` = %s, `Facebook_Recommendations` = %s, `Tribpedia_Related_Clicks` = %s, `Related_Clicks` = %s'''),
		'WW' : ('''INSERT INTO `WW_data_stories_{dimension}`( '''
			'''`date`, `path_article_md5`, `Combo_URL`, `Article`, `Pageviews`, `Scroll_Start`, `Scroll_25`, `Scroll_50`, `Scroll_75`, `Scroll_100`, `Scroll_Supplemental`, `Scroll_End`, `Time_15`, `Time_30`, `Time_45`, `Time_60`, `Time_75`, `Time_90`, `Comments`, `Republish`, `Emails`, `Tweets`, `Facebook_Recommendations`, `Tribpedia_Related_Clicks`, `Related_Clicks`'''
			''') VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`Combo_URL` = %s, `Article` = %s, `Pageviews` = %s, `Scroll_Start` = %s, `Scroll_25` = %s, `Scroll_50` = %s, `Scroll_75` = %s, `Scroll_100` = %s, `Scroll_Supplemental` = %s, `Scroll_End` = %s, `Time_15` = %s, `Time_30` = %s, `Time_45` = %s, `Time_60` = %s, `Time_75` = %s, `Time_90` = %s, `Comments` = %s, `Republish` = %s, `Emails` = %s, `Tweets` = %s, `Facebook_Recommendations` = %s, `Tribpedia_Related_Clicks` = %s, `Related_Clicks` = %s''')
	}
	data_quality = {
		'SCPR' : ('''INSERT INTO `SCPR_data_quality_{dimension}`( '''
			'''`date`, `GA_Users`, `MIP_Users`, `I_inDatabaseCameToSite`, `K_inDatabaseCameToSite`, `I_notInDatabaseCameToSite`, `K_notInDatabaseCameToSite`, `I_newSubscriberCameThroughEmail`, `K_newSubscriberCameThroughEmail`, `I_SubscribersThisWeek`, `K_SubscribersThisWeek`, `I_NewSubscribers`, `K_NewSubscribers`, `I_TotalDatabaseSubscribers`, `K_TotalDatabaseSubscribers`, `K_PercentDatabaseSubscribersWhoCame`, `EmailNewsletterClicks`, `I_databaseDonorsWhoVisited`, `K_databaseDonorsWhoVisited`, `I_donatedOnSiteForFirstTime`, `K_donatedOnSiteForFirstTime`, `I_totalDonorsOnSiteThisWeek`, `K_totalDonorsOnSiteThisWeek`, `I_totalDonorsInDatabase`, `K_totalDonorsInDatabase`, `K_percentDatabaseDonorsWhoCame`, `K_individualsWhoCameThisWeek`, `K_individualsInDatabase`, `K_percentDatabaseIndividualsWhoCame`, `Total_Identified_Donors_This_Week`, `I_databaseMembersWhoVisited`, `K_databaseMembersWhoVisited`, `I_loggedInOnSiteForFirstTime`, `K_loggedInOnSiteForFirstTime`, `I_totalMembersOnSiteThisWeek`, `K_totalMembersOnSiteThisWeek`, `I_totalMembersInDatabase`, `K_totalMembersInDatabase`, `K_percentDatabaseMembersWhoCame`'''
			''') VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`GA_Users` = %s, `MIP_Users` = %s, `I_inDatabaseCameToSite` = %s, `K_inDatabaseCameToSite` = %s, `I_notInDatabaseCameToSite` = %s, `K_notInDatabaseCameToSite` = %s, `I_newSubscriberCameThroughEmail` = %s, `K_newSubscriberCameThroughEmail` = %s, `I_SubscribersThisWeek` = %s, `K_SubscribersThisWeek` = %s, `I_NewSubscribers` = %s, `K_NewSubscribers` = %s, `I_TotalDatabaseSubscribers` = %s, `K_TotalDatabaseSubscribers` = %s, `K_PercentDatabaseSubscribersWhoCame` = %s, `EmailNewsletterClicks` = %s, `I_databaseDonorsWhoVisited` = %s, `K_databaseDonorsWhoVisited` = %s, `I_donatedOnSiteForFirstTime` = %s, `K_donatedOnSiteForFirstTime` = %s, `I_totalDonorsOnSiteThisWeek` = %s, `K_totalDonorsOnSiteThisWeek` = %s, `I_totalDonorsInDatabase` = %s, `K_totalDonorsInDatabase` = %s, `K_percentDatabaseDonorsWhoCame` = %s, `K_individualsWhoCameThisWeek` = %s, `K_individualsInDatabase` = %s, `K_percentDatabaseIndividualsWhoCame` = %s, `Total_Identified_Donors_This_Week` = %s, `I_databaseMembersWhoVisited` = %s, `K_databaseMembersWhoVisited` = %s, `I_loggedInOnSiteForFirstTime` = %s, `K_loggedInOnSiteForFirstTime` = %s, `I_totalMembersOnSiteThisWeek` = %s, `K_totalMembersOnSiteThisWeek` = %s, `I_totalMembersInDatabase` = %s, `K_totalMembersInDatabase` = %s, `K_percentDatabaseMembersWhoCame` = %s'''),
		'TT' : ('''INSERT INTO `TT_data_quality_{dimension}`( '''
			'''`date`, `GA_Users`, `MIP_Users`, `I_inDatabaseCameToSite`, `K_inDatabaseCameToSite`, `I_notInDatabaseCameToSite`, `K_notInDatabaseCameToSite`, `I_newSubscriberCameThroughEmail`, `K_newSubscriberCameThroughEmail`, `I_SubscribersThisWeek`, `K_SubscribersThisWeek`, `I_NewSubscribers`, `K_NewSubscribers`, `I_TotalDatabaseSubscribers`, `K_TotalDatabaseSubscribers`, `K_PercentDatabaseSubscribersWhoCame`, `EmailNewsletterClicks`, `I_databaseDonorsWhoVisited`, `K_databaseDonorsWhoVisited`, `I_donatedOnSiteForFirstTime`, `K_donatedOnSiteForFirstTime`, `I_totalDonorsOnSiteThisWeek`, `K_totalDonorsOnSiteThisWeek`, `I_totalDonorsInDatabase`, `K_totalDonorsInDatabase`, `K_percentDatabaseDonorsWhoCame`, `K_individualsWhoCameThisWeek`, `K_individualsInDatabase`, `K_percentDatabaseIndividualsWhoCame`, `Total_Identified_Donors_This_Week`, `I_databaseMembersWhoVisited`, `K_databaseMembersWhoVisited`, `I_loggedInOnSiteForFirstTime`, `K_loggedInOnSiteForFirstTime`, `I_totalMembersOnSiteThisWeek`, `K_totalMembersOnSiteThisWeek`, `I_totalMembersInDatabase`, `K_totalMembersInDatabase`, `K_percentDatabaseMembersWhoCame`'''
			''') VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`GA_Users` = %s, `MIP_Users` = %s, `I_inDatabaseCameToSite` = %s, `K_inDatabaseCameToSite` = %s, `I_notInDatabaseCameToSite` = %s, `K_notInDatabaseCameToSite` = %s, `I_newSubscriberCameThroughEmail` = %s, `K_newSubscriberCameThroughEmail` = %s, `I_SubscribersThisWeek` = %s, `K_SubscribersThisWeek` = %s, `I_NewSubscribers` = %s, `K_NewSubscribers` = %s, `I_TotalDatabaseSubscribers` = %s, `K_TotalDatabaseSubscribers` = %s, `K_PercentDatabaseSubscribersWhoCame` = %s, `EmailNewsletterClicks` = %s, `I_databaseDonorsWhoVisited` = %s, `K_databaseDonorsWhoVisited` = %s, `I_donatedOnSiteForFirstTime` = %s, `K_donatedOnSiteForFirstTime` = %s, `I_totalDonorsOnSiteThisWeek` = %s, `K_totalDonorsOnSiteThisWeek` = %s, `I_totalDonorsInDatabase` = %s, `K_totalDonorsInDatabase` = %s, `K_percentDatabaseDonorsWhoCame` = %s, `K_individualsWhoCameThisWeek` = %s, `K_individualsInDatabase` = %s, `K_percentDatabaseIndividualsWhoCame` = %s, `Total_Identified_Donors_This_Week` = %s, `I_databaseMembersWhoVisited` = %s, `K_databaseMembersWhoVisited` = %s, `I_loggedInOnSiteForFirstTime` = %s, `K_loggedInOnSiteForFirstTime` = %s, `I_totalMembersOnSiteThisWeek` = %s, `K_totalMembersOnSiteThisWeek` = %s, `I_totalMembersInDatabase` = %s, `K_totalMembersInDatabase` = %s, `K_percentDatabaseMembersWhoCame` = %s'''),
		'WW' : ('''INSERT INTO `WW_data_quality_{dimension}`( '''
			'''`date`, `GA_Users`, `MIP_Users`, `I_inDatabaseCameToSite`, `K_inDatabaseCameToSite`, `I_notInDatabaseCameToSite`, `K_notInDatabaseCameToSite`, `I_newSubscriberCameThroughEmail`, `K_newSubscriberCameThroughEmail`, `I_SubscribersThisWeek`, `K_SubscribersThisWeek`, `I_NewSubscribers`, `K_NewSubscribers`, `I_TotalDatabaseSubscribers`, `K_TotalDatabaseSubscribers`, `K_PercentDatabaseSubscribersWhoCame`, `EmailNewsletterClicks`, `I_databaseDonorsWhoVisited`, `K_databaseDonorsWhoVisited`, `I_donatedOnSiteForFirstTime`, `K_donatedOnSiteForFirstTime`, `I_totalDonorsOnSiteThisWeek`, `K_totalDonorsOnSiteThisWeek`, `I_totalDonorsInDatabase`, `K_totalDonorsInDatabase`, `K_percentDatabaseDonorsWhoCame`, `K_individualsWhoCameThisWeek`, `K_individualsInDatabase`, `K_percentDatabaseIndividualsWhoCame`, `Total_Identified_Donors_This_Week`, `I_databaseMembersWhoVisited`, `K_databaseMembersWhoVisited`, `I_loggedInOnSiteForFirstTime`, `K_loggedInOnSiteForFirstTime`, `I_totalMembersOnSiteThisWeek`, `K_totalMembersOnSiteThisWeek`, `I_totalMembersInDatabase`, `K_totalMembersInDatabase`, `K_percentDatabaseMembersWhoCame`'''
			''') VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) '''
			''' ON DUPLICATE KEY UPDATE '''
			'''`GA_Users` = %s, `MIP_Users` = %s, `I_inDatabaseCameToSite` = %s, `K_inDatabaseCameToSite` = %s, `I_notInDatabaseCameToSite` = %s, `K_notInDatabaseCameToSite` = %s, `I_newSubscriberCameThroughEmail` = %s, `K_newSubscriberCameThroughEmail` = %s, `I_SubscribersThisWeek` = %s, `K_SubscribersThisWeek` = %s, `I_NewSubscribers` = %s, `K_NewSubscribers` = %s, `I_TotalDatabaseSubscribers` = %s, `K_TotalDatabaseSubscribers` = %s, `K_PercentDatabaseSubscribersWhoCame` = %s, `EmailNewsletterClicks` = %s, `I_databaseDonorsWhoVisited` = %s, `K_databaseDonorsWhoVisited` = %s, `I_donatedOnSiteForFirstTime` = %s, `K_donatedOnSiteForFirstTime` = %s, `I_totalDonorsOnSiteThisWeek` = %s, `K_totalDonorsOnSiteThisWeek` = %s, `I_totalDonorsInDatabase` = %s, `K_totalDonorsInDatabase` = %s, `K_percentDatabaseDonorsWhoCame` = %s, `K_individualsWhoCameThisWeek` = %s, `K_individualsInDatabase` = %s, `K_percentDatabaseIndividualsWhoCame` = %s, `Total_Identified_Donors_This_Week` = %s, `I_databaseMembersWhoVisited` = %s, `K_databaseMembersWhoVisited` = %s, `I_loggedInOnSiteForFirstTime` = %s, `K_loggedInOnSiteForFirstTime` = %s, `I_totalMembersOnSiteThisWeek` = %s, `K_totalMembersOnSiteThisWeek` = %s, `I_totalMembersInDatabase` = %s, `K_totalMembersInDatabase` = %s, `K_percentDatabaseMembersWhoCame` = %s''')
	}
	data_newsletter = {
		'TT' : ('''INSERT INTO `TT_data_newsletter_{dimension}`( '''
			'''`date`, `Title`, `Subject`, `List`, `Send_Date`, `Send_Weekday`, `Total_Recipients`, `Successful_Deliveries`, `Soft_Bounces`, '''
			'''`Hard_Bounces`, `Total_Bounces`, `Times_Forwarded`, `Forwarded_Opens`, `Unique_Opens`, `Open_Rate`, `Total_Opens`, `Unique_Clicks`,'''
			'''`Click_Rate`, `Total_Clicks`, `Unsubscribes`, `Abuse_Complaints`, `Times_Liked_on_Facebook`)'''
			'''VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)''')
	}
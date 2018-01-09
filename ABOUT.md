-This application was built in a PHP language.
I have chosen PHP language for several reasons:
-	PHP is simple and dynamic language, and good choice for this application.
-	Currently I am working as integration engineer and develop application using Mule ESP (Java based), so I don’t have experience in any of the listed languages (I worked in MVC .net and this was not an option). for this i chose PHP as new language to learn


-What i learned in the process:
  This is my first application in PHP so i learned everything to complete this.

- known issues with example.
	1- Validation to select one of the three fields (destinationName, destinationCity, & regionIds) as these are alternatives. I think this should be done as client side - javascript, I did not implement this part.
	2- Validation to check that Min Trip Start Date is less than or equal Max Trip Start Date in case two filters are selected. Also "Min Trip Start Date" is greater than sysdate. I did not implement this part, and I think this should be done as client. 
	3- Same validation for "Min Star Rating" and "Max Star Rating" in case both are selected.
	4-I Could not find any value in the JSON output represent "min/maxTotalRate", So that i did not implement this filter - I need the expected value for this in order create valid input text (data type, min and max value…).
  
	



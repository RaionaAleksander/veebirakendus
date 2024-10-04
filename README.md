# veebirakendus

The site works only with this online store **Oomipood**, which has the following link:
https://www.oomipood.ee


**debug.html** is the main page of the Oomipood online store website, where I figured out how to get categories, product names and prices from there.


The only change that needs to be made is possibly changing the path on line 19 of the code in the script.js file.

19.  fetch('http://localhost:8000/api/crawl', {

Also, for the program to work correctly, you should have the **curl** extension for the PHP server language.
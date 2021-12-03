# SEPA-QR-for-Woocommerce (GDPR-compliant)
Plug-and-Play Plugin for Woocommerce
# Before you start
The plugin comes as is and free. However a real person has put real work into it. So if you use it please do s.th. good. Use your efforts, your time for beneficial projects or whatever!
# Installation
Nothing special:
In Woocommerce via "install" and upload or manually placing into the plugin folder.
# What it does
places an image with the SEPA-QR-Code in the
* thank-you-page after placing an order
* email you get from woocommerce

In the backend:
* the QR-code-Generator creates the QR-code locally. There is **no Google-API nor external server in use**!
* the QR-codegenerator is from [fellwell15](https://github.com/fellwell5/bezahlcode/)
* registers a get-parameter mxp_qr for testing purposes and to later to have a link for the cached QR-Code
* the prefix mxp is used throghout the plugin to avoid collisions with other plugins and functions. mxp stands for www.musicalexperten.de (musical experts). Remember where you've seen it first! ;-)

# Configuration / translation / if it does not work
The plugin comes with a little fallback: In case the BIC, IBAN, etc. are not shown open the **mxp-sepaqr.php** in the lines 45 to 50 you can hardcode some variables and translations. You'll find explanations in the comments.
## Advanced configuration of the qr-code itself
Have a look at [fellwell15](https://github.com/fellwell5/bezahlcode/)

# Testing and troubleshooting
## Simple way
Install the plugin and order s.th. in your shop using BACS (direct bank transfer).
## To test if the QR-Code generator is working
www.yourwebpage.de/?mxp_qr=something  = creates a real QR with dummyvalues 11-11
[Working example](https://www.musicalexperten.de/?mxp_qr=something)
## To find and existing cached QR-Code (which does *not* exist)
www.yourwebpage.de/?mxp_qr=351436ef4b279e1811a6c68a2dd58b1b 
results in a sad smiley. [Working example](https://www.musicalexperten.de/?mxp_qr=351436ef4b279e1811a6c68a2dd58b1b)

# Remarks
The function to cache a hashed QR is only needed if you want to use a link instead of a picture inside the email. Details in the program code.

# Support
The program has been written by a professional programmer - however fully free of charge. The program comes as is and we cannot give support. I have no clue about it and the programmer can't work for free!

# Full integration in Woocommerce
I am more then happy if someone integrates the code into the Woocommerce core!

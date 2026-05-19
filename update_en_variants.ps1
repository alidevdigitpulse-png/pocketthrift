$files = @(
    "en-GB.json", "en-AU.json", "en-CA.json", "en-NZ.json", "en-SG.json"
)

$basePath = "c:\laragon\www\pocketthrift\resources\lang\"

foreach ($file in $files) {
    $filePath = Join-Path $basePath $file
    $content = Get-Content $filePath -Raw
    
    # Add new keys after "All Regions"
    $content = $content -replace '("All Regions": "All Regions",)', '$1
    "All Categories": "All Categories",
    "Are you on the hunt for the best deals on car accessories, fashion, tech gadgets, and more? You''ve come to the right place! Here at PocketThrift": "Are you on the hunt for the best deals on car accessories, fashion, tech gadgets, and more? You''ve come to the right place! Here at PocketThrift",
    "you can find amazing discounts across a wide range of categories, from art supplies to baby products and everything in between. Our carefully curated selection of promo codes and deals helps you save big on top brands and essential items. Explore our site for unbeatable savings, and don''t miss out on fantastic bargains designed just for you. Start shopping smart today and enjoy significant discounts on your favorite products!": "you can find amazing discounts across a wide range of categories, from art supplies to baby products and everything in between. Our carefully curated selection of promo codes and deals helps you save big on top brands and essential items. Explore our site for unbeatable savings, and don''t miss out on fantastic bargains designed just for you. Start shopping smart today and enjoy significant discounts on your favorite products!",
    "Top Categories for Coupons and Deals in the": "Top Categories for Coupons and Deals in the",
    "Top Brands for Coupons and Deals in the": "Top Brands for Coupons and Deals in the",'
    
    # Add new keys after "Last Updated"
    $content = $content -replace '("Last Updated": "Last Updated",)', '$1
    "More Stores": "More Stores",
    "Get In Touch": "Get In Touch",
    "Address": "Address",
    "Phone": "Phone",
    "E-mail": "E-mail",
    "Message": "Message",
    "Subject": "Subject",
    "Full Name": "Full Name",
    "Fill out the form to send us a message. Our team will get back to you within 24 hours.": "Fill out the form to send us a message. Our team will get back to you within 24 hours.",
    "We''d love to hear from you! Get in touch with us.": "We''d love to hear from you! Get in touch with us.",
    "Join the PocketThrift experience and access special offers in every region that fits you. If you would like to shop through the website in a language you prefer, then you can do that too as you will find all the local savings offers around. Click here to change language and do smarter shopping now!": "Join the PocketThrift experience and access special offers in every region that fits you. If you would like to shop through the website in a language you prefer, then you can do that too as you will find all the local savings offers around. Click here to change language and do smarter shopping now!",
    "Find Great Deals from the Top Retailers": "Find Great Deals from the Top Retailers",
    "With the help of PocketThrift, one can save remarkably owing to the discounts provided by different retailers. If you are on a buying spree for your usual favorite brands or want to try out other stores that you have not visited before, you will appreciate our special collection that helps you shop better. Find the best Flash Sales with PocketThrift, the online shopping app built for you.": "With the help of PocketThrift, one can save remarkably owing to the discounts provided by different retailers. If you are on a buying spree for your usual favorite brands or want to try out other stores that you have not visited before, you will appreciate our special collection that helps you shop better. Find the best Flash Sales with PocketThrift, the online shopping app built for you.",
    "Stop Losing Time Looking for Discounts": "Stop Losing Time Looking for Discounts",
    "PocketThrift takes the backache away from you and helps you enjoy your shopping. Instead, we make it simple. Instead of making you click dozens of times to find the best price, PocketThrift shows new and exciting ways to save on purchases every day. This makes looking for great deals even more fun than it used to be.": "PocketThrift takes the backache away from you and helps you enjoy your shopping. Instead, we make it simple. Instead of making you click dozens of times to find the best price, PocketThrift shows new and exciting ways to save on purchases every day. This makes looking for great deals even more fun than it used to be.",
    "Benefit from Targeted Discounts with Daily Updates": "Benefit from Targeted Discounts with Daily Updates",
    "For continued savings from PocketThrift, check for daily discount updates as they come in. The goal remains the same, there will be refreshing of the deals every day so that customers don''t run out of even the last of offers provided by super stores. If you intend to purchase something, be sure to check PocketThrift first as the rates offered there will be cheaper. Smart shopping starts here with PocketThrift!": "For continued savings from PocketThrift, check for daily discount updates as they come in. The goal remains the same, there will be refreshing of the deals every day so that customers don''t run out of even the last of offers provided by super stores. If you intend to purchase something, be sure to check PocketThrift first as the rates offered there will be cheaper. Smart shopping starts here with PocketThrift!",
    "Coupons and Promo Codes last updated on": "Coupons and Promo Codes last updated on",'
    
    Set-Content -Path $filePath -Value $content -NoNewline
    Write-Output "Updated: $file"
}

Write-Output "All English variant files updated successfully!"

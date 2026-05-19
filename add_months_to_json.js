const fs = require('fs');
const path = require('path');

const langDir = 'c:/laragon/www/pocketthrift/resources/lang';
const months = {
    "January": "January",
    "February": "February",
    "March": "March",
    "April": "April",
    "May": "May",
    "June": "June",
    "July": "July",
    "August": "August",
    "September": "September",
    "October": "October",
    "November": "November",
    "December": "December"
};

fs.readdir(langDir, (err, files) => {
    if (err) {
        console.error('Error reading directory:', err);
        return;
    }

    files.forEach(file => {
        if (path.extname(file) === '.json') {
            const filePath = path.join(langDir, file);
            fs.readFile(filePath, 'utf8', (err, data) => {
                if (err) {
                    console.error(`Error reading file ${file}:`, err);
                    return;
                }

                try {
                    let json = JSON.parse(data);

                    // Add month keys if they don't exist
                    // Note: We are adding English defaults. The user will need to translate them 
                    // or we can try to be smart, but for now safe approach is adding keys.
                    // Actually, for specific languages we could add translations if we had them, 
                    // but manual review is safer as requested.
                    // However, I will add the keys.

                    Object.keys(months).forEach(month => {
                        if (!json[month]) {
                            json[month] = months[month];
                        }
                    });

                    // Formatting with 4 spaces
                    const formattedDetails = JSON.stringify(json, null, 4);

                    fs.writeFile(filePath, formattedDetails, 'utf8', (err) => {
                        if (err) {
                            console.error(`Error writing file ${file}:`, err);
                        } else {
                            console.log(`Updated ${file} with month keys`);
                        }
                    });
                } catch (parseError) {
                    console.error(`Error parsing JSON in ${file}:`, parseError);
                }
            });
        }
    });
});

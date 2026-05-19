const fs = require('fs');
const path = require('path');

const langDir = 'c:/laragon/www/pocketthrift/resources/lang';

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
                    // Parsing automatically handles duplicates by keeping the last occurrence
                    const json = JSON.parse(data);
                    
                    // Formatting with 4 spaces to match existing style roughly
                    const formattedDetails = JSON.stringify(json, null, 4);

                    fs.writeFile(filePath, formattedDetails, 'utf8', (err) => {
                        if (err) {
                            console.error(`Error writing file ${file}:`, err);
                        } else {
                            console.log(`Successfully cleaned up ${file}`);
                        }
                    });
                } catch (parseError) {
                    console.error(`Error parsing JSON in ${file}:`, parseError);
                }
            });
        }
    });
});

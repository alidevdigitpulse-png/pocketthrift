const fs = require('fs');
const path = require('path');

const langDir = 'c:/laragon/www/pocketthrift/resources/lang';

const translations = {
    'de': {
        "January": "Januar", "February": "Februar", "March": "März", "April": "April", "May": "Mai", "June": "Juni",
        "July": "Juli", "August": "August", "September": "September", "October": "Oktober", "November": "November", "December": "Dezember"
    },
    'es': {
        "January": "Enero", "February": "Febrero", "March": "Marzo", "April": "Abril", "May": "Mayo", "June": "Junio",
        "July": "Julio", "August": "Agosto", "September": "Septiembre", "October": "Octubre", "November": "Noviembre", "December": "Diciembre"
    },
    'fr': {
        "January": "Janvier", "February": "Février", "March": "Mars", "April": "Avril", "May": "Mai", "June": "Juin",
        "July": "Juillet", "August": "Août", "September": "Septembre", "October": "Octobre", "November": "Novembre", "December": "Décembre"
    },
    'it': {
        "January": "Gennaio", "February": "Febbraio", "March": "Marzo", "April": "Aprile", "May": "Maggio", "June": "Giugno",
        "July": "Luglio", "August": "Agosto", "September": "Settembre", "October": "Ottobre", "November": "Novembre", "December": "Dicembre"
    },
    'nl': {
        "January": "Januari", "February": "Februari", "March": "Maart", "April": "April", "May": "Mei", "June": "Juni",
        "July": "Juli", "August": "Augustus", "September": "September", "October": "Oktober", "November": "November", "December": "December"
    },
    'pl': {
        "January": "Styczeń", "February": "Luty", "March": "Marzec", "April": "Kwiecień", "May": "Maj", "June": "Czerwiec",
        "July": "Lipiec", "August": "Sierpień", "September": "Wrzesień", "October": "Październik", "November": "Listopad", "December": "Grudzień"
    },
    'no': {
        "January": "Januar", "February": "Februar", "March": "Mars", "April": "April", "May": "Mai", "June": "Juni",
        "July": "Juli", "August": "August", "September": "September", "October": "Oktober", "November": "November", "December": "Desember"
    },
    'fi': {
        "January": "Tammikuu", "February": "Helmikuu", "March": "Maaliskuu", "April": "Huhtikuu", "May": "Toukokuu", "June": "Kesäkuu",
        "July": "Heinäkuu", "August": "Elokuu", "September": "Syyskuu", "October": "Lokakuu", "November": "Marraskuu", "December": "Joulukuu"
    }
};

const fileMapping = {
    'de-DE.json': 'de', 'de-AT.json': 'de', 'de-CH.json': 'de', 'lb-LU.json': 'de', // Luxembourg often uses German/French, user kept it German-like previously or we can default to German for now based on context
    'es-ES.json': 'es', 'es-MX.json': 'es',
    'fr-FR.json': 'fr',
    'it-IT.json': 'it',
    'nl-NL.json': 'nl',
    'pl-PL.json': 'pl',
    'no-NO.json': 'no',
    'fi-FI.json': 'fi'
};

fs.readdir(langDir, (err, files) => {
    if (err) {
        console.error('Error reading directory:', err);
        return;
    }

    files.forEach(file => {
        if (fileMapping[file]) {
            const langKey = fileMapping[file];
            const trans = translations[langKey];
            const filePath = path.join(langDir, file);

            fs.readFile(filePath, 'utf8', (err, data) => {
                if (err) {
                    console.error(`Error reading file ${file}:`, err);
                    return;
                }

                try {
                    let json = JSON.parse(data);

                    // Update month translations
                    Object.keys(trans).forEach(month => {
                        json[month] = trans[month];
                    });

                    const formattedDetails = JSON.stringify(json, null, 4);

                    fs.writeFile(filePath, formattedDetails, 'utf8', (err) => {
                        if (err) {
                            console.error(`Error writing file ${file}:`, err);
                        } else {
                            console.log(`Translated months in ${file}`);
                        }
                    });
                } catch (parseError) {
                    console.error(`Error parsing JSON in ${file}:`, parseError);
                }
            });
        }
    });
});

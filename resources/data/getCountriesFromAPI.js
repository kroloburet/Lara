/**
 *
 * @see 'https://github.com/dr5hn/countries-states-cities-database'
 * other apis:  'https://restcountries.com/'
 */
export class getCountriesFromAPI {
    static apiUrl = `https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries%2Bstates%2Bcities.json`;

    static logErr(error) {
        console.error(`[getCountriesFromAPI] on line: ${error.lineNumber} `, error);
    }

    static async getRawJson() {
        try {
            const response = await fetch(this.apiUrl);
            return await response.json();
        } catch (e) {
            this.logErr(e)
        }
    }

    static async getFormatObject() {
        const rawJson = await this.getRawJson();
        const countries = {};
        for (const [key, value] of Object.entries(rawJson)) {
            countries[value.name] = {
                name: value.name,
                native: value.native,
                iso2: value.iso2,
                flag: value.emoji,
                region: value.region,
                currency: value.currency,
                currencySymbol: value.currency_symbol,
                phoneCode: `+${value.phone_code.replace(/^\+/, '')}`,
            };
        }
        return countries;
    }

    static async getFormatJson() {
        try {
            return JSON.stringify(await this.getFormatObject(), null, 1);
        } catch (e) {
            this.logErr(e);
        }
    }
}

getCountriesFromAPI.getFormatJson().then(json => console.log(json));

//import { BakongKHQR, khqrData, IndividualInfo } from "bakong-khqr";

    // ... rest of your code ...
    
// Remove this line:
 const { BakongKHQR, khqrData, IndividualInfo } = require("bakong-khqr"); 

const accountID = "004448060@aba";
const name = "YAUN MENGHONG";
const city = "Phnom Penh";
const currency = khqrData.currency.usd;
const amount = 0;

const optionalData = {
    currency,
    amount,
    billNumber: "ORD123456"
};

const individualInfo = new IndividualInfo(
    accountID,
    currency,
    name,
    city,
    optionalData
);

const khqr = new BakongKHQR();
const response = khqr.generateIndividual(individualInfo);

if (response.status.code === 0) {
    console.log(JSON.stringify({
        success: true,
        qr: response.data.qr,
        md5: response.data.md5
    }));
} else {
    console.log(JSON.stringify({
        success: false,
        error: response.status.message
    }));
}

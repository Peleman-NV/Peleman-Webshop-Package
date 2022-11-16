(function ($) {
    ('use strict');

    class valueResetter {
        constructor(element, value) {
            this.value = value;
            this.element = $(element);
        }

        resetValue() {
            this.element.val(this.value);
        }
    }

    $(function () {

        var _resetColors = $('#reset_colors');
        var _resetDetails = $('#reset_details');
        var _resetLogo = $('#reset_logos');
        var _submit = $("#submit");

        const _colorResetters = [
            new valueResetter('#main_color', "#2c5baa"),
            new valueResetter('#secondary_color', "#fdbe10"),
            new valueResetter('#main_text_color', "#444444"),
            new valueResetter('#menu_text_color', "#ffffff"),
            new valueResetter('#h1_text', 30,),
            new valueResetter('#h2_text', 25),
            new valueResetter('#h3_text', 20),
            new valueResetter('#h4_text', 17),
            new valueResetter('#h5_text', 17),
            new valueResetter('#h6_text', 17),
        ];

        const _detailResetters = [
            new valueResetter('#company_name', 'CopyShop'),
            new valueResetter('#address_text', 'Turnhoutsebaan 5'),
            new valueResetter('#postal_text', '2110'),
            new valueResetter('#city_text', 'Wijnegem'),
            new valueResetter('#country_text', 'BE'),
            new valueResetter('#email_text', 'info@copyshop.com'),
            new valueResetter('#phone_number_text', '(+32) 847-291-4353'),
            new valueResetter('#facebook_text', 'https://facebook.com/copyshop-peleman'),
            new valueResetter('#twitter_text', 'https://twitter.com/copyshop-peleman'),
            new valueResetter('#instagram_text', 'https://instagram.com/copyshop-peleman'),
            new valueResetter('#linkedin_text', 'https://linkedin.com/copyshop-peleman'),
            new valueResetter('#notificationtext', "COPYSHOP has been your creative printing and copying center for more than 30 years, in the heart of Antwerp. Thanks to our many years of experience, our team guarantees a professional and flexible service and excellent quality." + '\n' +
                "At COPYSHOP you can copy as well as digitally print, in color and in black and white, from name card to large format poster. In addition to our very extensive range of paper, we offer you various solutions for finishing your printed matter. And if you’re looking for a fun way to please someone, you can choose from a range of personalized gift items." + '\n' +
                "For more non - binding information, you can always contact us on(+32) 847 - 291 - 4353 or via info@copyshop.com.Or just drop by.See you soon in COPYSHOP."),
            , new valueResetter('#termsconditions_text', "1. Application of the general Terms and Conditions of Sale" + '\n' +
                "        1.1. The Present General Terms and Conditions of Sale apply to all sales and services carried out by the Vendor, except in case of a contrary written agreement between Parties. The Buyer explicitly renounces the application of its own general conditions, even if these would be posterior to the present General Terms and Conditions of Sale. In order to be valid, each deviation to the Present General Terms and Conditions of Sale must be subject to an explicit, prior and written agreement between Parties. Should any other document stipulate other terms, such as catalogues, brochures, promotion materials, etc. such stipulations shall never bind the Vendor." + '\n' +
                "        1.2. Any failure or delay by the Vendor in exercising any right or remedy pursuant to these General terms and Conditions of Sale will not impair such right or remedy to be construed as a waiver of it and will not preclude its exercise at any subsequent time." + '\n' +
                "        2. Definitions In these General Terms and Conditions of Sales" + '\n' +
                "        Buyer means every individual (natural entity) or legal entity which has or will potentially have a contractual relation with the Vendor. The Buyer can be a Professional as well as a Consumer;" + '\n' +
                "        Vendor means Peleman Industries NV, with registered office at Rijksweg 7, 2870 Puurs St Amands, Belgium, and registered in the Crossroads Bank of Enterprises under the number 405.746.743, as well as its associated companies;" + '\n' +
                "        Consumer means every individual (natural entity) who acquires the Vendor’s Goods, exclusively for non-professional purposes;" + '\n' +
                "        Professional means every individual or legal entity that durably pursues an economic purpose;" + '\n' +
                "        The Vendor and the Buyer are referred to individually as a Party and collectively as the Parties, as the case may be;" + '\n' +
                "        Website(s) means https://webshop.peleman.com or any other domain name registered by the Vendor;" + '\n' +
                "        Goods means all the products and services which the Vendor offers on its Website(s) or in any other sustained way;" + '\n' +
                "        Delivery Address means the address which the Buyer reports in its order for the Goods to be delivered." + '\n' +
                "        3. Offers and Orders" + '\n' +
                "        3.1 All offers made by the Vendor shall be merely illustrative and shall not bind the Vendor. Without prejudice to article" + '\n' +
                "        3.2 Every order constitutes an absolutely irrevocable purchase in respect of the Buyer. The Vendor for its part is only bound as from a written confirmation of its acceptance of the order. 3.2. In case the Buyer is a Consumer, the Contract will be validly concluded on the day on which the receipt or invoice which corresponds to the order of the Consumer, in paper or in electric form, is provided to the Consumer by the Vendor. The Consumer shall not be able to cancel the order once the receipt or invoice which corresponds to this order is provided to the Consumer. If the Consumer orders the Vendor’s Goods through its Website(s), and unless the ordered Goods have to be manufactured as per specifications of the Buyer (art. 47, Â§4, 2Â° AMP), the Consumer shall be able to cancel the order within 14 calendar days after Delivery of the ordered Goods, without being due any compensation or penalty." + '\n' +
                "        3.3. The Vendor reserves the right to demand the payment, ipso juro and without prior notice being served, of an irrevocable fixed-sum termination penalty if the Buyer cancels an order, in full or in part, in breach of the articles 3.1 or 3.2. This penalty will amount to fifteen per cent (15%) of the total amount of the cancelled order." + '\n' +
                "        3.4. The request of the Buyer for the Vendor to manufacture and provide a draft design or model implies an obligation in respect of the Buyer to order the Vendor’s Goods in case he decides to purchase Goods of the category which the Vendor offers. In case the Buyer fails to comply with this obligation, the Vendor shall be allowed to demand compensation for the manufacturing and transport costs of the draft designs and/or models." + '\n' +
                "        3.5. The Buyer can demand printer’s proofs, which shall be printed on regular paper or plastic. In case the Buyer demands high-end printer’s proofs or models/designs that equal the result of the final print, the Vendor shall be allowed to charge the costs of this printer’s proof or model/design." + '\n' +
                "        3.6. The Buyer will have to inform the Vendor in written and within 48 hours after receipt of the printer’s proof or draft model/design of any change desired to the final print or model/design." + '\n' +
                "        3.7. Descriptions, drawings, pictures, colors, measures and specifications of Goods delivered by the Vendor are approximate only. The Buyer cannot use occurring differences, which do not differ substantially from technical or esthetical specifications, against the Vendor, unless expressly agreed otherwise in writing." + '\n' +
                "        3.8. The actual delivered amount of Goods may deviate 10% from the ordered amount of Goods." + '\n' +
                "        3.9. All well molds, clichÃ©s, moulds, tools and equipment, which have been ordered and paid by the Buyer, and which were meant for the execution of a specific assignment given by the Buyer, will be stored and maintained by the Vendor for a 2 year term, which will commence at the moment on which the Vendor receives total payment for these Goods." + '\n' +
                "        4. Delivery and acceptance" + '\n' +
                "        4.1. Delivery is deemed to take place when the ordered Goods are collected by the Buyer at the premises of the Vendor or when they are received at the Delivery Address. Unless agreed upon otherwise in writing, the ordered Goods will be delivered by the Vendor at the Delivery Address for the account of the Buyer. The Buyer will only be allowed to collect the Ordered Products at the Vendors premises if Parties explicitly agree upon this." + '\n' +
                "        4.2. The Vendor guarantees a delivery of the ordered Goods to the Buyer such as they existed at the time of its written acceptance of the order or, in case the Buyer is a Consumer, at the time on which the receipt or invoice which corresponds to the Order is provided to the Consumer." + '\n' +
                "        4.3. The Vendor will appeal to a third party for the transport of the Goods from the premises of the Vendor to the Delivery Address (hereinafter referred to as ‘Transporter’). The risk of loss, degeneration and destruction of the Goods shall pass onto the Buyer at the moment on which the Vendor hands the Goods over to the transporter." + '\n' +
                "        4.4. Without prejudice to article 4.6, the terms of delivery are merely indicative and are not binding. No delay can give rise to any indemnification whatsoever or to the cancellation of the Order. The Buyer is obliged to accept orders that are delivered in several times." + '\n' +
                "        4.5. Without prejudice to article 7, in case the Buyer is a Consumer, the Vendor cannot be held liable for exceeding the terms of delivery, unless the Vendor exceeds the term of delivery by more than 14 calendar days." + '\n' +
                "        4.6. The Buyer is obliged to take delivery of the Goods at the date which Parties agreed upon. The Buyer shall make sure that there is enough space at the Delivery Address in order for the delivery to take place in an untroubled manner. Unless agreed upon otherwise in writing, the Vendor will only be held to deliver the Ordered Products in a room on the ground floor of the Delivery Address. The Buyer is obliged to sign the delivery note at the moment of delivery." + '\n' +
                "        4.7. Immediately upon delivery, the Buyer shall examine the quality and quantity of the Goods. Visible defects of the Goods are to be communicated by registered letter to the Vendor within ten (10) working days after delivery at the latest, in the absence of which complaints will not be admissible. The use of the Goods by the Buyer implies his irrevocable acceptance of a lack of conformity of the Goods." + '\n' +
                "        4.8. Without prejudice to article 4.10 of these GTCS, hidden defects of the Goods are to be communicated by registered letter to the Vendor within ten (10) working days after discovery of the flaw. Such complaints shall no longer be admissible six (6) months after the delivery of the Goods. The use of the Goods by the Buyer after discovery of the flaw implies his irrevocable acceptance of lack of conformity of the Goods." + '\n' +
                "        4.9. In case the Buyer is a Consumer, hidden defects of the Goods are to be communicated by registered letter to the Vendor within ten (10) working days after discovery of the flaw. Such complaints shall no longer be admissible two (2) years after the delivery of the Goods. The use of the Goods by the Buyer after discovery of the flaw implies his irrevocable acceptance of lack of conformity of the Goods." + '\n' +
                "        4.10. The responsibility of the Vendor, both with regard to the visible and hidden defects, can never exceed the amount that is the equivalent to the invoiced amount for the relevant Order." + '\n' +
                "        4.11. The Vendor is not deemed nor obliged to know or take into consideration the particular use of the Goods by the Buyer. Therefore, the Vendor is never responsible for a use of the Goods by the Buyer that deviates from a normal use." + '\n' +
                "        4.12. The Buyer is allowed to return the Goods to the Vendor insofar as the Goods show a visible or hidden defect and upon prior and timely written complaint/notification by the Buyer. The Vendor’s reception of such returned Goods or its acceptance of a return of the Goods, does not imply any recognition of responsibility or correctness of the complaint. The costs of return will be borne by the Vendor under the express condition that a defect is found. A return of the Goods will only be accepted if they are in their original condition upon delivery." + '\n' +
                "        4.13. The Vendor is only liable for flaws in the design, materials or manufacturing of the Goods which are the object of the contract. The Vendor is not liable for defects resulting out of actions or negligence of the Buyer or a third person, including: unfit or illegal use, improper assembly or operation, natural wear and tear, improper treatment and maintenance, use of the product in combination with improper gear, etc." + '\n' +
                "        4.14. If the Buyer complains about an alleged non-conformity or defect, which turns out to be non-existing or to be a non-conformity or defect for which the Vendor is not liable, the Vendor has the right to demand compensation / indemnity / damages for the costs it encountered due to the unjustified complaint." + '\n' +
                "        5. Price and payment" + '\n' +
                "        5.1. The prices stated in the catalogues, brochures or other promotional materials are merely indicative. The sales price will be the price mentioned in the Order Confirmation. In case the Buyer is a Consumer, the price will be the price of the invoice or receipt provided to the Consumer, which the Consumer did not cancel within a term of 14 calendar days after reception of this invoice or receipt." + '\n' +
                "        5.2. Unless otherwise agreed upon in written, carriage charges, call-out charges and any possible installation charges are not included in the purchase price. Any taxes and levies on the purchase price, as well as any other taxes or other charges arising between the time the order is placed and the time of delivery will be borne by the Buyer." + '\n' +
                "        5.3. All invoices are payable cash at the registered office of the Vendor. Any advance made will be deducted from the total balance. In the case of deferred payment, payment must be made into the bank account of the Vendor, by the means and by the due date specified on the invoice. If no due date is indicated on the invoice, payment must be made within thirty (30) calendar days after the invoice date." + '\n' +
                "        5.4. In the event of (even partial) absence of payment of an invoice at its expiration date, the Vendor may, automatically and without prior notice, charge interests on arrears at the legal rate, increased with 4,5%. Furthermore, without prejudice to the Vendor’s right to claim full compensation of the actually suffered damages (if those would be superior to the fixed compensation), the Vendor will, automatically and without prior notice, be entitled to a fixed compensation of fifteen per cent (15%) of the total Price of the unpaid invoice." + '\n' +
                "        5.5. In case the Buyer is a Consumer, and without prejudice to the other articles of these GTCS, the Buyer will likewise be entitled to a fixed compensation of fifteen per cent (15%) of the total Price of the invoice, in case the Vendor fails to comply with its obligations regarding this invoice." + '\n' +
                "        5.6. In case the Buyer is a Professional, the interest rate charged on arrears (cf. art. 5.4) will be the interest rate which is stipulated in the Act on combating late payment in commercial transactions of 2 August 2002." + '\n' +
                "        5.7. Any absence of payment causes the loss of all discounts and incentives for the Buyer." + '\n' +
                "        5.8. In case of (partial) non-payment of an invoice at its expiration date, any other non-expired invoices will become due automatically and without prior notice." + '\n' +
                "        5.9. In case of (partial) non-payment of an invoice at its expiration date, the Vendor will be entitled to immediately suspend the execution of all pending Orders and deliveries without prior notice." + '\n' +
                "        5.10. The Vendor may at any time demand guaranties and securities of the Buyer which it deems appropriate in view of the good execution of the Buyer’s commitments." + '\n' +
                "        5.11. Unless the Buyer is a Consumer, any form of set-off by the Buyer between the credits and debts that exist mutually between the Vendor and the Buyer is expressly excluded." + '\n' +
                "        5.12. The vendor can at any moment, even in the event of bankruptcy, judicial reorganization, collective debt settlement or any other form of insolvency procedure in respect of the Buyer, execute a set-off between the credits and debts that exist mutually between the Vendor and the Buyer. This set-off can be executed, whatever may be the object, form or origin of the mutual credits and debts. This set-off will be calculated in Euros after, if necessary, conversion of the foreign currency at the expense of the Buyer." + '\n' +
                "        5.13. Received payments shall be used as payment for the oldest outstanding claims." + '\n' +
                "        5.14. In the event that the solvency of the Buyer appears to be compromised, e.g. in the event of non-payment or late payment of an invoice, the Vendor is entitled to ask the Buyer for a retainer with respect to any stocks held for the Buyer and with respect to any further deliveries. In the event that the Buyer does not agree with any such retainer asked by the Vendor, the latter has the right to immediately and unilaterally terminate the agreement with the Buyer, without serving notice and without judicial intervention." + '\n' +
                "        5.15. Please note that the transaction amount will be directly credited to your credit card at the time of the transaction." + '\n' +
                "        If you choose another type of payment, the amount will also be debited from your card immediately after confirmation." + '\n' +
                "        6. Retention of title" + '\n' +
                "        6.1. The Vendor retains property of the Goods until payment in full by the Buyer, including payment of the price, the costs, the interests and possible compensations." + '\n' +
                "        6.2. As long as the Buyer has not paid in full, it is prohibited to use the Goods by means of payment, to pledge or to encumber the Goods with any type of security." + '\n' +
                "        6.3. As long as the Buyer has not paid in full, it will affix a sign on the Goods which clearly indicates that they are the property of the Vendor. The Buyer undertakes to immediately inform the Vendor by registered letter if a third party seizes the Goods." + '\n' +
                "        6.4. As long as the Buyer has not paid in full, it is obliged to store the Goods in an impeccable state and in an adapted and tidy place consistent with the most rigorous norms and security prescriptions that are common in the sector. As from delivery and until payment in full, the Buyer is obliged to insure the Goods against all risks that are common in the sector (including but not limited to: degeneration, fire, moisture and theft) and to provide the Vendor with a copy of the insurance policy." + '\n' +
                "        6.5. The Buyer is obliged to inform the Vendor if the Goods will be stored in a building that is not the property of the Buyer. The Buyer will communicate the identity of the owner of the building to the Vendor, and will undertake all efforts which can reasonably be expected in order to inform the owner of the building about the fact that the Vendor is the owner of the delivered Goods." + '\n' +
                "        6.6. The above articles with regard to the retention of title shall not affect the provisions relating to the transfer of risk (cf. article 4.4)." + '\n' +
                "        7. Limitation of liability" + '\n' +
                "        7.1. The Vendor is not responsible for disturbances that date back from the period before the acceptance of the Order." + '\n' +
                "        7.2. The Vendor is not responsible for the damage caused by the Goods if that damage is not only caused by a flaw in the Goods but also by a fault of the Buyer or a third party." + '\n' +
                "        7.3. The Vendor cannot be held liable for a minor fault or gross error." + '\n' +
                "        7.4. In case the Buyer is a Consumer, the Vendor carries full liability for the loss of life, physical injury and damage to health when it is proven that such damage was caused by a default of the Vendor, the Vendor’s legal representative or agent and on the condition that there is a causal link between the injury and the default. In case the Buyer is a Professional, and without prejudice to the Belgian Act of 25 February 1991 with regard to the manufacturer’s liability for products with lacks, the Vendor cannot be held liable for the loss of life, physical injury and damage to health, except in case of fraud or intentional fault in respect of the Vendor." + '\n' +
                "        7.5. The Vendor only accepts legal liability in accordance with statutory provisions for direct material damages which may arise from serious misconduct, fraud or wilful misconduct. Liability of the Vendor for any indirect damage(s), subsequent loss including (but not limited to) loss of profit, reduction of production, etc. is expressly excluded." + '\n' +
                "        7.6. The Vendor is not liable for serious misconduct or wilfull misconduct of Vendor’s employees, agents, representatives, etc. in connection with the execution of their professional activities." + '\n' +
                "        7.7. If the Goods show any lack in conformity, the Buyer can, notwithstanding the Vendor’s right to choose between the return of the price and the replacement of the Goods, only claim the replacement of the Goods. The Buyer can never claim any other form of compensation whatsoever in case of a lack in conformity of the Goods." + '\n' +
                "        7.8. If the Vendor chooses to repair the defected Good, the Vendor has the right to instruct a third party to conduct the repair. Any delay caused by this third party does not give the Buyer the right to call upon the remedies specified in article 7.7." + '\n' +
                "        7.9. The Buyer does not have the right to terminate the contract if the Vendor notifies the Buyer within a reasonable time about when and how the Vendor shall replace or repair the defected Good. Only if there is a substantial non-conformity or defect, and the Vendor fails to replace or to repair the non-conform or defected Good, the Buyer can terminate the agreement and can demand that the Vendor reimburses the purchase price. If there is a non-substantial non-conformity or defect, the Buyer only can demand a proportional discount." + '\n' +
                "        7.10. In any case the Buyer’s claim for compensation / indemnity / damages cannot exceed the initial purchase price, not even if there is a substantial non-conformity or defect." + '\n' +
                "        7.11. The Vendor is not liable for the Buyer’s or a third person’s operational loss, loss of time, loss of profit or any other direct or indirect loss caused by a defect in the Goods." + '\n' +
                "        7.12. Except in case of fraud or intentional fault, the Vendor’s contractual and non-contractual liability vis-Ã -vis the Buyer is limited to the amount covered by the insurance. In any event, the Vendor’s contractual liability will be limited to the price of the contract from which the liability results, and the Vendor’s non-contractual liability will be limited to the amount of 10.000 EUR per claim, even in case of gross error. In any event, the Vendor’s liability will be limited to 25.000 EUR for all claims that result from the same contract of the same cause." + '\n' +
                "        7.13. Without prejudice to article 4, every claim in compensation of the Buyer vis-Ã -vis the Vendor is legally null and void if the Buyer has not presented its case to the competent court within a period of six (6) months as from the date upon which the circumstance that serves as a basis for his claim became or should have become known to him. In case the Buyer is a Consumer, the Buyer will be required to present its claim in compensation vis-Ã -vis the Vendor to the competent court within a period of two (2) years as from the date upon which the circumstance that serves as a basis for his claim became or should have become known to him." + '\n' +
                "        7.14. Neither party shall be liable for performance delays of non-performance of its obligations due to causes beyond its reasonable control (Force Majeure), except for payment obligations. Force Majeure Events will include, without limitation, war, riots, insurrection, severe disturbance in the security of the internet, technical failures, strikes, unauthorised access and/or intrusions into the Website’s servers, strikes of all natures and computer or telephone failure. If a party cites an event that constitutes a Force Majeure Event, it must inform the other party within five working days of the occurrence or threatened occurrence of the event. In the event of Force Majeure, both Parties shall undertake all reasonable efforts to limit the consequences of the Force Majeure Event. Should the cited Force Majeure event continue for over two (2) months, either party shall be allowed to terminate the contract without judicial intervention, and without any party being due compensation to the other party." + '\n' +
                "        7.15. The provisions of this article 7 do not prevent the application of the Belgian Act on Manufacturers Liability (25 February 1991), nor the application of the Belgian Act on Market Practices and Consumer Protection (6 April 2010) in case the Buyer is a Consumer." + '\n' +
                "        8. Privacy" + '\n' +
                "        8.1. Within the scope of the online ordering process, the contracting process, or any other process that should be necessary in order to determine the relation between Parties, the Vendor will gather personal details of the Buyer, such as (but not limited to): the e-mail address, the name, the postal address, the (cell)phone number, the date of birth and the profession. Furthermore, the Vendor may ask for some financial details, such as credit card-numbers or bank account-numbers, in order to secure its payment. By placing an Order via the Vendor’s Website(s) or any other medium, the Buyer gives its explicit permission to process these personal details in accordance to the conditions of these General Terms and Conditions and the privacy statement." + '\n' +
                "        8.2. The Vendor will only process the Buyer’s personal data in accordance with the Belgian Act of 8 December 1992 safeguarding the Personal Privacy as regards the processing of personal data, and in accordance with its Privacy Statement." + '\n' +
                "        9. Communications" + '\n' +
                "        Within the scope of their relations, both parties accept the proof by electronic means (e.g.: e-mail, backup, etc.). The Buyer expressly accepts the use of electronic invoices within the meaning of Royal Decree nÂ°1 of 29 December 1992." + '\n' +
                "        10. Severability" + '\n' +
                "        10.1. If any (part of a) provision of the present General Terms and Conditions is held to be invalid or unenforceable, or contrary to imperative law or the public order, then such provision will (so far as it is invalid or unenforceable) have no effect and will be deemed not to be included in the present General Terms and Conditions of Sale, but without invalidating any of the remaining provisions. The Parties must then use all reasonable endeavours to replace the invalid or unenforceable provisions by a valid and enforceable substitute provision the effect of which is as close as possible to the intended effect of the invalid unenforceable provision." + '\n' +
                "        11. Governing law and jurisdiction" + '\n' +
                "        11.1. The Parties’ contractual relations are governed by and must be construed and interpreted in accordance with the Laws of Belgium (expressly excluding the application of the United Nations’ Convention on Contracts for the International Sale of Goods (CISG) of 11 April 1980)." + '\n' +
                "        11.2. Unless the Buyer is a Consumer, all claims must be submitted to the exclusive jurisdiction of the courts of Mechelen." + '\n' +
                "        12. Warranty" + '\n' +
                "        12.1. All our products have a standard and legal 2-year warranty. This means that an article should be in good condition and work properly. When this wouldn’t be the case, we’ll offer an appropriate solution. Depending on the article we’ll replace, repair or refund the goods.")];
        const _logoResetters = [
            new valueResetter('#upload_one', "https://demowebshop.peleman.com/copyshop/wp-content/uploads/2022/09/logo-color.png"),
            new valueResetter('#upload_two', "https://demowebshop.peleman.com/copyshop/wp-content/uploads/2022/09/logo-white.png"),
        ]

        _resetColors.on("click", function () {
            console.log("resetting color values...");

            if (confirm("Are you sure you want to reset the styling to the default?")) {
                _colorResetters.forEach(function (resetter) {
                    resetter.resetValue()
                });
                _submit.click();
            }
        });

        _resetDetails.on("click", function () {
            console.log("resetting store details...");

            if (confirm("Are you sure you want to reset the copyshop's details to the defaults?")) {
                _detailResetters.forEach(function (resetter) {
                    resetter.resetValue()
                });
                _submit.click();
            }
        });

        _resetLogo.on("click", function () {
            console.log("resetting Logos...");

            if (confirm("Are you sure you want to reset the Logos to the defaults?")) {
                _logoResetters.forEach(function (resetter) {
                    resetter.resetValue()
                });
                _submit.click();
            }
        });
    })

})(jQuery);


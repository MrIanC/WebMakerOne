<?php

if (isset($_POST['openingHoursSpecification']))
    foreach ($_POST['openingHoursSpecification'] as $t => $v) {
        if (empty($_POST['openingHoursSpecification'][$t]['opens']))
            unset($_POST['openingHoursSpecification'][$t]);
    }
if (isset($_POST['sameAs']))
    foreach ($_POST['sameAs'] as $t => $v) {
        if (empty($_POST['sameAs'][$t]))
            unset($_POST['sameAs'][$t]);
    }
if (isset($_POST['sameAs']))
    if (empty($_POST['sameAs']))
        unset($_POST['sameAs']);

if (!empty($_POST)) {
    echo "Yeah";
    file_put_contents("../resources/json/business.json", json_encode($_POST),JSON_PRETTY_PRINT);
}


$jsonLD = json_decode(file_get_contents("../resources/json/business.json"), true);
$currentUrl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$currentUrl .= $_SERVER['HTTP_HOST'];
;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="no-index, no-follow">
    <meta name="favicon" content="favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/resources/css/fonts.css" rel="stylesheet">
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <div class="display-1 fw-bold">Company Card Details</div>
        </div>
        <form method="post">

            <input type="hidden" name="@context" value="https://schema.org">
            <div class="fw-bold mt-3">Business Type</div>
            <select class="form-select"
                onchange="if ($(this).val()=='custom') {$('#attype').show();$('#attype').val('')} else {$('#attype').hide();$('#attype').val($(this).val())};">
                <option><?php echo $jsonLD['@type'] ?? ""; ?></option>
                <option value="Attorney">Attorney</option>
                <option value="AutoRepair">Auto Repair</option>
                <option value="Bakery">Bakery</option>
                <option value="BarOrPub">Bar or Pub</option>
                <option value="BeautySalon">Beauty Salon</option>
                <option value="BedAndBreakfast">Bed and Breakfast</option>
                <option value="CafeOrCoffeeShop">Café or Coffee Shop</option>
                <option value="CarRental">Car Rental</option>
                <option value="ClothingStore">Clothing Store</option>
                <option value="Dentist">Dentist</option>
                <option value="DryCleaningOrLaundry">Dry Cleaning or Laundry</option>
                <option value="ElectronicsStore">Electronics Store</option>
                <option value="FastFoodRestaurant">Fast Food Restaurant</option>
                <option value="FinancialService">Financial Service</option>
                <option value="Florist">Florist</option>
                <option value="FurnitureStore">Furniture Store</option>
                <option value="GasStation">Gas Station</option>
                <option value="Gym">Gym</option>
                <option value="HairSalon">Hair Salon</option>
                <option value="Hotel">Hotel</option>
                <option value="InsuranceAgency">Insurance Agency</option>
                <option value="JewelryStore">Jewelry Store</option>
                <option value="LegalService">Legal Service</option>
                <option value="LiquorStore">Liquor Store</option>
                <option value="Locksmith">Locksmith</option>
                <option value="LodgingBusiness">Lodging Business</option>
                <option value="MedicalClinic">Medical Clinic</option>
                <option value="MovieTheater">Movie Theater</option>
                <option value="MusicStore">Music Store</option>
                <option value="NightClub">Night Club</option>
                <option value="Optician">Optician</option>
                <option value="PetStore">Pet Store</option>
                <option value="Pharmacy">Pharmacy</option>
                <option value="Physician">Physician</option>
                <option value="Plumber">Plumber</option>
                <option value="RealEstateAgent">Real Estate Agent</option>
                <option value="Restaurant">Restaurant</option>
                <option value="School">School</option>
                <option value="ShoppingCenter">Shopping Center</option>
                <option value="ShoeStore">Shoe Store</option>
                <option value="Spa">Spa</option>
                <option value="SportingGoodsStore">Sporting Goods Store</option>
                <option value="Supermarket">Supermarket</option>
                <option value="TattooParlor">Tattoo Parlor</option>
                <option value="TaxiService">Taxi Service</option>
                <option value="TravelAgency">Travel Agency</option>
                <option value="VeterinaryCare">Veterinary Care</option>
                <option value="Winery">Winery</option>
                <option value="Zoo">Zoo</option>
                <option value="custom">Other</option>
            </select>
            <input class="form-control mt-3" type="text" id="attype" name="@type" style="display:block;"
                value="<?php echo $jsonLD['@type'] ?? ""; ?>">

            <div class="fw-bold mt-3">Details</div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Name</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="name"
                        value="<?php echo $jsonLD['name'] ?? ""; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Image</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="url" name="image"
                        value="<?php echo $jsonLD['image'] ?? "" ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Site Url</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="url" name="url"
                        value="<?php echo !empty($jsonLD['url']) ? $jsonLD['url'] : $currentUrl; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Telephone</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="phone" name="telephone"
                        value="<?php echo $jsonLD['telephone'] ?? ""; ?>">
                </div>
            </div>




            <div class="fw-bold mt-3">Address</div>
            <input type="hidden" name="address[@type]" value="PostalAddress">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Street Address</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="address[streetAddress]"
                        value="<?php echo $jsonLD['address']['streetAddress'] ?? ""; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Locality</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="address[addressLocality]"
                        value="<?php echo $jsonLD['address']['addressLocality'] ?? ""; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Region</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="address[addressRegion]"
                        value="<?php echo $jsonLD['address']['addressRegion'] ?? ""; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Code</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="address[postalCode]"
                        value="<?php echo $jsonLD['address']['postalCode'] ?? ""; ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <div class="m-3 form-label">Country</div>
                </div>
                <div class=" col-12 col-sm-6">
                    <input class="m-1 form-control" type="text" name="address[addressCountry]"
                        value="<?php echo $jsonLD['address']['addressCountry'] ?? ""; ?>">
                </div>
            </div>

            <div class="fw-bold mt-3">Trading Days and Times</div>
            <div class="row">
                <?php
                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $t => $dow) {
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="input-group m-3">
                            <div class="form-control"><?php echo $dow ?>:</div>
                            <input type="hidden" name="openingHoursSpecification[<?php echo $t ?>][@type]"
                                value="OpeningHoursSpecification">
                            <input type="hidden" name="openingHoursSpecification[<?php echo $t ?>][dayOfWeek]"
                                value="<?php echo $dow ?>">
                            <input class="btn btn-light" type="time"
                                name="openingHoursSpecification[<?php echo $t ?>][opens]"
                                value="<?php echo $jsonLD['openingHoursSpecification'][$t]['opens'] ?? ""; ?>">
                            <input class="btn btn-light" type="time"
                                name="openingHoursSpecification[<?php echo $t ?>][closes]"
                                value="<?php echo $jsonLD['openingHoursSpecification'][$t]['closes'] ?? ""; ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="fw-bold mt-3">Social Media Links</div>
            <div class="row">
                <?php
                for ($t = 0; $t < 10; $t++) { ?>
                    <div class="col-12 col-sm-6">
                        <div class="m-3 form-label">Social Media</div>
                    </div>
                    <div class=" col-12 col-sm-6">
                        <input class="form-control" type="url" name="sameAs[]" value="<?php echo $jsonLD['sameAs'][$t] ?? ""; ?>">
                    </div>
                <?php } ?>
            </div>
            <div class="text-end mb-5">
            <input class="btn btn-primary" type="submit" value="Save">
            </div>
        </form>
    </div>
    <script>
        <?php echo "jsondata =" . json_encode($_POST, JSON_PRETTY_PRINT); ?>;
        console.log(jsondata);
    </script>

</body>

</html>
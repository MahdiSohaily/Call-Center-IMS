function filterCode(element) {
  if (!element.value) return;

  const codes = element.value.split("\n").filter((code) => code.length > 0);

  const filteredCodes = codes
    .map((code) => {
      const removedText = code.replace(/\[[^\]]*\]/g, "");
      const parts = removedText.includes(":")
        ? removedText.split(":")
        : removedText.split(",");
      const rightSide = parts[1]
        ? parts[1].trim().replace(/[^a-zA-Z0-9 ]/g, "")
        : "";
      return rightSide || removedText.replace(/[^a-zA-Z0-9 ]/g, "");
    })
    .filter((item) => item && !/[^a-zA-Z0-9 ]/g.test(item));

  let finalCodes = filteredCodes.filter(
    (item) => item.split(" ")[0].length > 4
  );

  finalCodes = finalCodes.map((item) => {
   item = item.split(" ");
    if (count(item) >= 2) {
     partOne =item[0];
     partTwo =item[1];
      if (
        !preg_match("/[a-zA-Z]{4,}/i",partOne) &&
        !preg_match("/[a-zA-Z]{4,}/i",partTwo)
      ) {
        returnpartOne.$partTwo;
      }
    }
    return item[0];
  });
  finalCodes = finalCodes.filter((item) => {
    const consecutiveChars = item.match(/[a-zA-Z]{4,}/g);
    return !consecutiveChars;
  });

  element.value = finalCodes.join("\n");
}

console.log(
  filterCode(`Niyayesh Rahimi, [03/05/1402 09:35 ق.ظ]
سلام صبحتون بخیر روز خوبی داشته باشین

Xxx Zolfkhni Alireza, [03/05/1402 09:41 ق.ظ]
924063x200

Xxx Zolfkhni Alireza, [03/05/1402 09:41 ق.ظ]
2674032804 asli

Xxx Zolfkhni Alireza, [03/05/1402 09:41 ق.ظ]
9475037100  asli

Xxx Zolfkhni Alireza, [03/05/1402 09:41 ق.ظ]
218323k000

Xxx Zolfkhni Alireza, [03/05/1402 09:44 ق.ظ]
218302g700  chin

Xxx Zolfkhni Alireza, [03/05/1402 09:44 ق.ظ]
87621-C5020

Xxx Zolfkhni Alireza, [03/05/1402 10:02 ق.ظ]
Yokh?

Niyayesh Rahimi, [03/05/1402 10:03 ق.ظ]
قیمت داری

Xxx Zolfkhni Alireza, [03/05/1402 10:03 ق.ظ]
Mojodi

Xxx Zolfkhni Alireza, [03/05/1402 10:03 ق.ظ]
Nadaram felan

Niyayesh Rahimi, [03/05/1402 10:03 ق.ظ]
1500

Xxx Zolfkhni Alireza, [03/05/1402 10:03 ق.ظ]
Faghat hamin bod?

Niyayesh Rahimi, [03/05/1402 10:03 ق.ظ]
are

Xxx Zolfkhni Alireza, [03/05/1402 10:20 ق.ظ]
36100-2G300 kore hamin code

Xxx Zolfkhni Alireza, [03/05/1402 10:25 ق.ظ]
Inam dari

Niyayesh Rahimi, [03/05/1402 10:26 ق.ظ]
361002G200

Xxx Zolfkhni Alireza, [03/05/1402 10:26 ق.ظ]
Na gire moshtari

Xxx Zolfkhni Alireza, [03/05/1402 10:56 ق.ظ]
Eshgholi

Xxx Zolfkhni Alireza, [03/05/1402 11:29 ق.ظ]
92102-1R520

Niyayesh Rahimi, [03/05/1402 11:29 ق.ظ]
29

Xxx Zolfkhni Alireza, [03/05/1402 12:15 ب.ظ]
49575-2b000`)
);

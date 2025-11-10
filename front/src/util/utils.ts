export const getTextColor = (hex: string) => {
  const c = hex.substring(1);
  const rgb = parseInt(c, 16);
  
  const r = (rgb >> 16) & 0xff;
  const g = (rgb >> 8) & 0xff;
  const b = (rgb >> 0) & 0xff;

  const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
  return luminance > 186 ? "#000000" : "#FFFFFF";
};

export const rgbGetTextColor = (rgb: string) => {
  const colors = rgb.match(/\d+/g)
  
  const r = colors![0]
  const g = colors![1]
  const b = colors![2]

  const luminance = 0.299 * parseInt(r) + 0.587 * parseInt(g as string) + 0.114 * parseInt(b as string);
  console.log(luminance);
  console.log(luminance > 186);

  return luminance > 186 ? "#000000" : "#FFFFFF";
}
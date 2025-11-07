export const getTextColor = (hex: string) => {
  const c = hex.substring(1);
  const rgb = parseInt(c, 16);
  console.log(rgb);
  const r = (rgb >> 16) & 0xff;
  console.log(`R do RGB: ${r}`);
  const g = (rgb >> 8) & 0xff;
  console.log(`G do RGB: ${g}`);
  const b = (rgb >> 0) & 0xff;
  console.log(`B do RGB: ${b}`);
  const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
  return luminance > 186 ? "#000000" : "#FFFFFF";
};

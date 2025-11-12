import { Notify } from 'quasar'

const getTextColor = (hex: string) => {
  const c = hex.substring(1);
  const rgb = parseInt(c, 16);

  const r = (rgb >> 16) & 0xff;
  const g = (rgb >> 8) & 0xff;
  const b = (rgb >> 0) & 0xff;

  const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
  return luminance > 186 ? "#000000" : "#FFFFFF";
};

const rgbGetTextColor = (rgb: string) => {
  const colors = rgb.match(/\d+/g);

  const r = colors![0];
  const g = colors![1];
  const b = colors![2];

  const luminance =
    0.299 * parseInt(r) +
    0.587 * parseInt(g as string) +
    0.114 * parseInt(b as string);

  return luminance > 186 ? "#000000" : "#FFFFFF";
};

export const getTextColorFromDB = (textColor: string) => {
  if (textColor.startsWith("rgb")) {
    return rgbGetTextColor(textColor);
  } else if (textColor.startsWith("#")) {
    return getTextColor(textColor);
  }
};

const formatter = Intl.NumberFormat("pt-BR", {
  style: "currency",
  currency: "BRL",
});

export const formattedValue = (value: number) => {
  return formatter.format(value);
};

export const notify = (message: string, color: string, icon: string) => {
  Notify.create({
    icon,
    color,
    multiLine: true,
    message,
    position: "bottom-right",
  });
};

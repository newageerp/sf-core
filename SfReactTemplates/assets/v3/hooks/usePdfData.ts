import { axiosInstance } from "@newageerp/v3.bundles.utils-bundle";
import { useState } from "react";
import { PropsId } from "../../_custom/models-cache-data/types";

type PdfProps = {
  template: string;
  skipStamp?: boolean;
  skipSign?: boolean;
} & PropsId;

type PdfResponse = {
  url: string;
  viewUrl: string;
  downloadUrl: string;
  printUrl: string;
  fileName: string;
};

export function usePdfData(props: PdfProps) {
  const [pdfData, setPdfData] = useState<PdfResponse>();
  const [isLoading, setLoading] = useState(false);

  const loadPdfData = async (
    id: number,
    template: string,
    skipStamp?: boolean,
    skipSign?: boolean,
  ) => {
    let url = `/app/nae-core/pdf/pdfInfo/invoice-outgoing/${template}/${id}?pdf=1`;
    if (skipStamp) {
      url += "&skipStamp=true";
    }
    if (skipSign) {
      url += "&skipSign=true";
    }
    const res = await axiosInstance.get(url);
    return res.data;
  };

  const onDownload = async () => {
    let urlToOpen = "";
    if (!pdfData) {
      setLoading(true);
      const _pdfData: PdfResponse = await loadPdfData(
        props.id,
        props.template,
        props.skipStamp,
        props.skipSign,
      );
      setPdfData(_pdfData);
      setLoading(false);
      urlToOpen = _pdfData.downloadUrl;
    } else {
      urlToOpen = pdfData.downloadUrl;
    }
    if (urlToOpen) {
      window.open(urlToOpen, "_blank");
    }
  };

  const onReset = () => {
    setPdfData(undefined);
  };

  const loadData = async () => {
    setLoading(true);
    const _pdfData: PdfResponse = await loadPdfData(
      props.id,
      props.template,
      props.skipStamp,
      props.skipSign,
    );
    setLoading(false);
    setPdfData(_pdfData);
    return _pdfData;
  };
  return { pdfData, isLoading, onReset, loadPdfData: loadData, onDownload };
}

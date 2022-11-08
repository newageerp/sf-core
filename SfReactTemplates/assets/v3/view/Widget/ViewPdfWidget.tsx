import React, { Fragment, useState } from 'react'
import { PdfLinesContainer } from '@newageerp/ui.components.element.pdf-lines-container';
import { PdfLine } from '@newageerp/ui.components.element.pdf-line';
import { PropsId } from '../../../_custom/models-cache-data/types';
import axios from 'axios'
import { PopupPdf } from '@newageerp/ui.popups.base.popup-pdf';
import { PdfWindow } from '@newageerp/ui.files.pdf.pdf-window';
import { MailsForm } from '@newageerp/ui.mails.mails-form';
import { PopupMail } from '@newageerp/ui.popups.base.popup-mail';
import { useTranslation } from 'react-i18next';
import { Template, useTemplateLoader } from '../../templates/TemplateLoader';
import TemplateLoader from '../../templates/TemplateLoader';

type PdfResponse = {
    url: string,
    viewUrl: string,
    downloadUrl: string,
    printUrl: string,
    fileName: string,
}

type PdfItemProps = {
    id: number,

    schema: string,

    title: string,
    template: string,
    skipStamp?: boolean,
    skipSign?: boolean,
}

interface Props {
    items: Template[],
    title?: string,
}

export default function ViewPdfWidget(props: Props) {
    const { t } = useTranslation();

    const [sign, setSign] = useState(true);
    const toggleSign = () => setSign(!sign);

    let key = "pdf-container-sign";
    if (!sign) {
        key = "pdf-container-no-sign";
    }

    return (
        <PdfLinesContainer
            key={key}
            title={props.title ? props.title : t("PDF dokumentai")}
            signature={
                {
                    state: sign,
                    toggleState: toggleSign
                }
            }
        >
            <TemplateLoader
                templateData={{ skipSign: !sign }}
                templates={props.items}
            />

        </PdfLinesContainer>
    )
}


export const ViewPdfItem = (props: PdfItemProps) => {
    const { data: tData } = useTemplateLoader();

    const skipStamp = tData.skipSign || props.skipStamp;
    const skipSign = tData.skipSign || props.skipSign;

    const loadPdfData = async (id: number, template: string) => {
        let url = `/app/nae-core/pdf/pdfInfo/${props.schema}/${template}/${id}?pdf=1`;
        if (skipStamp) {
            url += '&skipStamp=true'
        }
        if (skipSign) {
            url += '&skipSign=true'
        }
        const res = await axios.get(url);
        return res.data;
    }

    const [pdfData, setPdfData] = useState<PdfResponse>();
    const [showPdf, setShowPdf] = useState(false);
    const [showEmail, setShowEmail] = useState(false);
    const [isLoading, setLoading] = useState(false);

    const onReset = () => {
        setPdfData(undefined);
    }

    const onDownload = async () => {
        let urlToOpen = "";
        if (!pdfData) {
            setLoading(true);
            const _pdfData: PdfResponse = await loadPdfData(props.id, props.template);
            setPdfData(_pdfData);
            setLoading(false);
            urlToOpen = _pdfData.downloadUrl;
        } else {
            urlToOpen = pdfData.downloadUrl;
        }
        if (urlToOpen) {
            window.open(urlToOpen, '_blank');
        }
    }

    const onPrint = async () => {
        let urlToOpen = "";
        if (!pdfData) {
            setLoading(true);
            const _pdfData: PdfResponse = await loadPdfData(props.id, props.template);
            setLoading(false);
            setPdfData(_pdfData);
            urlToOpen = _pdfData.printUrl;
        } else {
            urlToOpen = pdfData.printUrl;
        }
        if (urlToOpen) {
            window.open(urlToOpen, '_blank');
        }
    }

    const onClick = async () => {
        if (!pdfData) {
            setLoading(true);
            const _pdfData: PdfResponse = await loadPdfData(props.id, props.template);
            setLoading(false);
            setPdfData(_pdfData);
            setShowPdf(true);
        } else {
            setShowPdf(true);
        }
    }

    const onSend = async () => {
        if (!pdfData) {
            setLoading(true);
            const _pdfData: PdfResponse = await loadPdfData(props.id, props.template);
            setLoading(false);
            setPdfData(_pdfData);
            setShowEmail(true);
        } else {
            setShowEmail(true);
        }
    }

    return (
        <Fragment>
            <PdfLine title={props.title} onReset={pdfData ? onReset : undefined} onDownload={onDownload} onPrint={onPrint} onClick={onClick} onSend={onSend} loading={isLoading} />
            {showPdf && !!pdfData &&
                <PopupPdf onClose={() => setShowPdf(false)} title={props.title}>
                    <PdfWindow
                        pdf={pdfData}
                        onBack={() => setShowPdf(false)}
                        onEmail={onSend}
                        inPopup={true}
                    />
                </PopupPdf>
            }
            {showEmail && !!pdfData &&
                <PopupMail>
                    <MailsForm
                        onSend={() => setShowEmail(false)}
                        onBack={() => setShowEmail(false)}
                        extraData={
                            {
                                id: props.id,
                                pdf: true,
                                schema: props.schema,
                                template: 'pdf',
                            }
                        }
                        files={
                            [
                                {
                                    name: pdfData.fileName,
                                    link: pdfData.downloadUrl
                                }
                            ]
                        }
                    />
                </PopupMail>
            }
        </Fragment>);
}
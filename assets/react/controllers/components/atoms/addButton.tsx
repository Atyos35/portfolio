const AddIcon = require('../../../../icons/addIcon.png');

type Props = {
    onClick: (e: React.MouseEvent<HTMLSpanElement>) => void;
};

export default function AddButton({ onClick }: Props) {
    return (
        <span
            onClick={onClick}
            className="ml-3 cursor-pointer"
            title="Ajouter"
        >
            <img 
                src={AddIcon} 
                alt="Ajouter" 
                className="w-5 h-5 min-w-[1.25rem] min-h-[1.25rem] align-middle" 
            />
        </span>
    );
}